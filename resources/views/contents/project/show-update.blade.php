@extends('layouts.default')
@section('content')
  <div class="container-mobile" style="padding: 20px 20px 100px;">
    <article class="nice-read">
      <br>
      <br>
      <a href="{{ route('project.newGetShow', $update['project']['slug']) }}" class="btn btn-primary">Kembali ke Campaign</a>
      <header class="page-header">
        <h2>
          {{ $update->title }}
          @if(Auth::user())
            @if(Auth::user()->id == $project['user_id'] or Auth::user()->is_superadmin == 1)
              <small>
                <a href="{{ route('project.getEditUpdate', $update['id']) }}">
                  Edit
                </a>
              </small>
            @endif
          @endif
        </h2>
        <p>{{ formatTime( $update->created_at ) }}</p>
      </header>
      <section class="body">
        {!! $update->description !!}
      </section>
    </article>
  </div>
@stop
