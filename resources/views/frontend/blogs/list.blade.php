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

    <div id="blog-page-slider" class="blog-page-slider owl-carousel owl-theme">
      @foreach($blogs_slider as $item)
        @php
          $isExists = $item->photo && file_exists(public_path('uploads/blogs/thumb/' . $item->photo));
          $filename = $isExists ? $item->photo : 'default.png';
          $src = url('uploads/blogs/thumb/' . $filename);
        @endphp
        <a href="{{ route('frontend.blog.detail', array('slug' => $item->slug)) }}" class="blogpage-slider-item item">
          <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
          <div class="text">
            <span>{{ $item->category ? $item->category->name : 'Tanpa Kategori' }}</span>
            <div class="title">{{ $item->title }}</div>
            <div class="main">
              {{ $item->content }}
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="blog-list">
      @foreach($blogs as $item)
        @php
          $isExists = $item->photo && file_exists(public_path('uploads/blogs/thumb/' . $item->photo));
          $filename = $isExists ? $item->photo : 'default.png';
          $src = url('uploads/blogs/thumb/' . $filename);
        @endphp
        <a href="{{ route('frontend.blog.detail', array('slug' => $item->slug)) }}" class="blog-list-box media">
          <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
          <div class="text media-body">
            <span>{{ $item->category ? $item->category->name : 'Tanpa Kategori' }}</span>
            <div class="title">{{ $item->title }}</div>
            <div class="main">
              {{ $item->content }}
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <nav class="blog-list-pagin">
      {{ $blogs->render() }}
    </nav>
  </div>
</div>
@endsection