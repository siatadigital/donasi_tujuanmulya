<?php

namespace App\Repositories\Media;

use Config;
use Image;

class Uploader
{
    protected $path;

    /**
     * Save media
     *
     * @param  File $file
     * @param  string $filename
     * @param  array $cropData
     * @return void
     */
    public function saveImage($file, $filename, $cropData = [])
    {
        $path = $this->getPath();
        $img = Image::make($file)->orientate();

        // \Debugbar::info($cropData);

        // crop if exist
        if (!empty($cropData) and is_array($cropData)) {
            $img = $img->crop(
                $cropData['width'], 
                $cropData['height'], 
                $cropData['x'], 
                $cropData['y']
            );
        }

        $constraint = function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };

        // save to large
        $img->resize(getOption('media_image_large_size')['w'], null, $constraint)
            ->save($path . 'large/' . $filename);

        // save to medium
        $img->resize(getOption('media_image_medium_size')['w'], null, $constraint)
            ->save($path . 'medium/' . $filename);

        // save to small
        $img->resize(getOption('media_image_small_size')['w'], null, $constraint)
            ->save($path . 'small/' . $filename);
    }

    /**
     * Remove media
     *
     * @param  string $filename
     * @return void
     */
    public function removeImage($filename)
    {
        $path = $this->getPath();
        @unlink($path . 'large/' . $filename);
        @unlink($path . 'medium/' . $filename);
        @unlink($path . 'small/' . $filename);
    }

    public function saveUserPicture($file, $filename)
    {
        $path = str_finish(Config::get('web.upload_dir.user'), '/');
        $img = Image::make($file);
        // save to large 720x720 (assuming pixels)
        $img->encode('jpg', 75)->save($path . 'large/' . $filename);
        // save to medium
        $img->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 75)->save($path . 'medium/' . $filename);
        // save to small
        $img->resize(null, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 75)->save($path . 'small/' . $filename);
    }

    public function removeUserPicture($filename)
    {
        $path = str_finish(Config::get('web.upload_dir.user'), '/');
        @unlink($path . 'large/' . $filename);
        @unlink($path . 'medium/' . $filename);
        @unlink($path . 'small/' . $filename);
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path ?: public_path('media/images/');
    }

}
