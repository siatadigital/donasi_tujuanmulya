@extends('frontend.master')

@section('content')
<div class="blog-page">
  <div class="container">
    <h1 class="blog-page-title">
      Blog
    </h1>

    <nav class="navbar-blog navbar navbar-expand-md">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#blog-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="blog-nav">
        <ul class="navbar-nav mx-auto">
          @foreach($blog_categories as $item)
            <li class="nav-item">
              <a href="{{ route('frontend.blog.category', array('slugCategory' => $item->slug)) }}" class="nav-link">{{ $item->name }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </nav>

    <div class="blog-detail">
      @if ($blog)
      @php
        $isExists = $blog->photo && file_exists(public_path('uploads/blogs/' . $blog->photo));
        $filename = $isExists ? $blog->photo : 'default.png';
        $src = url('uploads/blogs/' . $filename);
      @endphp
      <img src="{{ $src }}">

      <div class="text">
        <span>{{ $blog->category ? $blog->category->name : 'Tanpa Kategori' }}</span>
        <div class="title">{{ $blog->title }}</div>
        <div class="date">
          {{ date('d F Y', strtotime($blog->created_at)) }}
        </div>
        <div class="main">
          {!! $blog->content !!}
        </div>
      </div>
      @else
      <div class="text">
        <div class="main">
          Konten tidak ditemukan
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection