@extends('admin.master')

@section('title', 'RTL - Syarat & Ketentuan')

@section('content')
<div class="section-header">
  <h1>Syarat & Ketentuan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Syarat & Ketentuan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Syarat & Ketentuan</h2>
  <p class="section-lead">
    Form untuk ubah konten di halaman Syarat & Ketentuan
  </p>

  <form action="{{ route('admin.terms_and_agreements.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Syarat & Ketentuan</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Konten',
                'type' => 'textarea',
                'name' => 'content',
                'value' => $content,
                'class' => 'tinymce',
                'error' => $errors->first('content'),
            ])
            @endcomponent
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </div>
  </form>
</div>
@endsection

@section('js')
@if (session()->has('message'))
<script>
  iziToast.success({
    title: 'Berhasil!',
    message: '{{ session("message") }}',
    position: 'topRight'
  });
</script>
@endif

<script>
tinymce.init({
  menubar: false,
  selector: 'textarea.tinymce',
  plugins: 'jbimages fullscreen link code',
  toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent link | jbimages youtube | fullscreen | code",
  relative_urls: false,
  setup: function(editor) {
    editor.on('change', function() {
      tinymce.triggerSave();
    });
  }
});
</script>
@endsection
