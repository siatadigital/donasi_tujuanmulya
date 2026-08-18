@extends('frontend.master')

@section('content')
<div class="blog-page">
  <div class="container">
    <div class="blog-detail">
      @php
        $isExists = $page->photo && file_exists(public_path('uploads/pages/' . $page->photo));
        $filename = $isExists ? $page->photo : 'default.png';
        $src = url('uploads/pages/' . $filename);
      @endphp
      @if ($page->photo)
        <img src="{{ $src }}">
      @endif

      <div class="text">
        <div class="title">{{ $page->title }}</div>
        <div class="date">
          {{ date('d F Y', strtotime($page->created_at)) }}
        </div>
        <div class="main">
          {!! $page->content !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection