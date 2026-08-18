<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Media\MediaRepository;
use App\Repositories\Media\Uploader;
use Illuminate\Support\Str;
use Input;

class MediaController extends Controller
{
    /**
     * @var MediaRepository
     */
    protected $media;

    public function __construct(MediaRepository $media)
    {
        $this->media = $media;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function upload($slug = null)
    {
        $media = null;
        if ($slug) {
            $media = $this->media->findByIdentifier($slug, 'slug');
        }

        $file = Input::file('file');
        $files = Input::file('files');

        if (!empty($files)) {
            $result_files = [];
            foreach (Input::file('files') as $key => $file) {
                array_push($result_files, $this->_handleUpload($file, $media));
            }
            return $result_files;
        } else {
            return $this->_handleUpload($file, $media);
        }
    }

    public function delete($id)
    {
    	$media = app('App\Models\Media')->find($id);
    	Uploader::removeImage($media['filename']);
    	$media->delete();
    	return true;
    }

    private function _handleUpload($file, $media = null)
    {
        $max_size = 5000000;
        if ($file->isValid() and $file->getClientSize() <= $max_size) {
            $title = !empty($media)
            ? $media['title']
            : preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName());
            $filename = !empty($media)
            ? $media['filename']
            : str_random(20) . '.' . $file->getClientOriginalExtension();

            // save image
            $cropData = null;
            if (Input::has('width') and Input::has('height') and Input::has('left') and Input::has('top')) {
                $cropData = [
                    'width' => (integer) ceil(Input::get('width')),
                    'height' => (integer) ceil(Input::get('height')),
                    'x' => (integer) ceil(Input::get('left')),
                    'y' => (integer) ceil(Input::get('top')),
                ];
            }
            (new Uploader)->saveImage($file, $filename, $cropData);

            // store to database
            $userID = auth()->user() ? auth()->user()->id : '';
            $this->media->create([
                'user_id' => $userID,
                'filename' => $filename,
                'title' => $title,
                'slug' => Str::slug($title),
                'mime_type' => $file->getClientMimeType(),
            ]);

            // handle for user save avatar
            if (Input::get('type') == 'avatar') {
                $user = auth()->user();
                $user->avatar = $filename;
                $user->save();
            }

            // handle for user save cover
            if (Input::get('type') == 'cover') {
                $user = auth()->user();
                $user->cover = $filename;
                $user->save();
            }

            return $filename;
        }
    }
}
