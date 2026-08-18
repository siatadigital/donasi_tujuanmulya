@extends('layouts.default')
@section('head')
  <script>
    $(function(){
      $("form").on('submit',function(e){
        var title = $("#title").val(),
            description = $("#description").val(),
            project_id = parseInt($("#project_id").val());

        if( title == '' || description == '' || project_id == '' || project_id == 0) {
          swal("Terjadi Kesalahan!",'Anda tidak diperbolehkan mengosongi field ataupun project.',"error");
          $("#project_id").focus();
          e.preventDefault();
        }
      })
    })
  </script>
@stop
@section('content')
  <div class="container-mobile" style="padding: 20px;margin-bottom:100px;">
    <header class="page-header">
      <h2>Update Info Terbaru Campaign</h2>
    </header>


    {!! Form::open() !!}
      <div class="form-group">
        <label>Project</label>
        <select name="project_id" id="project_id" class="form-control">
          <option value="0">--Pilih Project--</option>
          @foreach($projects as $item)
            @if ($project_id == $item->id)
            <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
            @else
            <option value="{{ $item->id }}">{{ $item->title }}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Title</label>
        {!! Form::text('title','',['class'=>'form-control','Title','required','id'=>'title']) !!}
      </div>

      <div class="form-group">
        <label>Description</label>
        {!! Form::textarea('description','',['class'=>'summernote','id'=>'description']) !!}
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-lg btn-primary" id="submit">Submit</button>
      </div>
    {!! Form::close() !!}
  </div>
@stop