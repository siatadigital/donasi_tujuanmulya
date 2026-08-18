<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct()
    {

    }

    public function getIndex()
    {
        $data = [
            'title' => 'Semua Berita',
            'blogs' => Blog::orderBy('created_at', 'desc')->paginate(20),
        ];
        return view('admin::contents.blogs.index', $data);
    }

    public function getPublish()
    {
        $data = [
            'title' => 'Berita Publish',
            'blogs' => app('App\Models\Blog')->where('status', 'publish')->orderBy('created_at', 'desc')->paginate(20),
        ];
        return view('admin::contents.blogs.index', $data);
    }

    public function getDraft()
    {
        $data = [
            'title' => 'Berita Draft',
            'blogs' => app('App\Models\Blog')->where('status', 'draft')->orderBy('created_at', 'desc')->paginate(20),
        ];
        return view('admin::contents.blogs.index', $data);
    }

    public function getEdit($id)
    {
        $data = [
            'title' => 'Edit Berita',
            'blog' => app('App\Models\Blog')->where('status', 'publish')->where('id', $id)->first(),
        ];
        return view('admin::contents.blogs.edit', $data);
    }

}
