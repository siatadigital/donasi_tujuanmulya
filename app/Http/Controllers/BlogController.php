<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Blog\BlogRepository;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\BlogViewer;

class BlogController extends Controller
{
	protected $blog;

	public function __construct(BlogRepository $blog)
	{
		return $this->blog = $blog;
	}

    public function getIndex()
    {
        $data = [
            'blogs_artikel' => $this->blog->getArtikel(9),
            'blogs_update' => $this->blog->getUpdate(9),
        ];
        return view('contents.blog.index',$data);
    }

    public function getShow($slug)
    {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])){
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif(isset($_SERVER['HTTP_X_FORWARDED'])){
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}elseif(isset($_SERVER['HTTP_FORWARDED'])){
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}elseif(isset($_SERVER['REMOTE_ADDR'])){
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}else{
			$ipaddress = 'UNKNOWN';
		}

        // return view('contents.blog.show');
		$blog = $this->blog->findBySlug($slug);

		$getBlog = Blog::where('slug',$slug)->first();

		$checkHit = BlogViewer::where('blog_id',$getBlog->id)->where('ip_address',$ipaddress)->first();

		if($checkHit){
			BlogViewer::where('id',$checkHit->id)->update([
				'hit' => $checkHit->hit + 1,
			]);
		}else{
			BlogViewer::create([
				'blog_id' => $getBlog->id,
				'ip_address' => $ipaddress,
				'hit' => 1,
			]);
		}

		if ($blog->status === 'draft') {
			return abort(404);
		}

		$sumHit = BlogViewer::where('blog_id',$getBlog->id)->sum('hit');

        $data = [
            'title' => $blog->title,
            'blog' => $blog,
            'blogs_artikel' => Blog::where('blog_categories_id',$getBlog->blog_categories_id)->where('status', 'publish')->with('user','blog_categories')->take(5)->get(),
			'count_hit' => $sumHit
        ];
        return view('contents.blog.show',$data);
    }

    public function getCreate()
    {
			if(auth()->user()->is_superadmin != 1) {
				return redirect('/');
			}
		
			$data['kategori'] = BlogCategory::get();

    	return view('contents.blog.create', $data);
    }

    public function postCreate(Request $request)
    {
			if(auth()->user()->is_superadmin != 1) {
				return redirect('/');
			}

			if(!empty($request->slug) && Blog::where('slug',$request->slug)->first()){
				$data['kategori'] = BlogCategory::get();

    			return redirect()->back()->withErrors('Custom Slug sudah ada, mohon untuk menggunakan yang lainnya !');
			}

        // return $request->all();
    	$blog = $this->blog->create($request->all());
    	return redirect()->route('blog.getShow', $blog['slug']);
    }

    public function getEdit($slug)
    {
			if(auth()->user()->is_superadmin != 1) {
				return redirect('/');
			}

			$blog = $this->blog->findBySlug($slug);
			$data = [
					'title' => $blog->title,
					'blog' => $blog,
					'kategori' => BlogCategory::get()
			];
			return view('contents.blog.edit',$data);
    }

    public function putEdit(Request $request, $slug)
    {
			if(auth()->user()->is_superadmin != 1) {
				return redirect('/');
			}

			if(!empty($request->edit_slug) && Blog::where('slug',$request->edit_slug)->first()){
				$data['kategori'] = BlogCategory::get();

    			return redirect()->back()->withErrors('Custom Slug sudah ada, mohon untuk menggunakan yang lainnya !');
			}

			$req = $request->except('edit_slug');
			if(!empty($request->edit_slug)){
				$req['slug'] = $request->edit_slug;
			}

			$blog = $this->blog->findBySlug($slug);
			$blog = $this->blog->update($blog, $req);
			return redirect()->route('admin.blogs.getIndex');
    }

    public function destroy($slug)
    {
			if(auth()->user()->is_superadmin != 1) {
				return redirect('/');
			}

			$blog = $this->blog->findBySlug($slug);
			$blog = $this->blog->delete($blog->id);
			return redirect()->route('admin.blogs.getIndex');
    }
}
