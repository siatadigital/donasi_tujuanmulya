@extends('admin.master')

@section('title', 'RTL - Tambah Artikel')

@section('content')
<div class="section-header">
  <h1>Tambah Artikel</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.blogs.index') }}">Artikel</a></div>
    <div class="breadcrumb-item">Tambah Artikel</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Artikel</h2>
  <p class="section-lead">
    Form untuk tambah artikel
  </p>

  <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Artikel</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Judul',
                'type' => 'text',
                'name' => 'title',
                'value' => old('title'),
                'required' => TRUE,
                'error' => $errors->first('title'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Konten',
                'type' => 'textarea',
                'name' => 'content',
                'value' => old('content'),
                'class' => 'tinymce',
                'required' => TRUE,
                'error' => $errors->first('content'),
            ])
            @endcomponent
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h4>Lainnya</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Publikasikan',
                'type' => 'select',
                'name' => 'is_published',
                'options' => [
                    0 => 'Tidak',
                    1 => 'Ya',
                ],
                'value' => 0,
                'error' => $errors->first('is_published'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Kategori',
                'type' => 'select',
                'name' => 'category_id',
                'value' => old('category_id'),
                'options' => $categories,
                'error' => $errors->first('category_id'),
                'required' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Foto',
                'type' => 'image',
                'name' => 'photo',
                'value' => old('photo'),
                'required' => TRUE,
                'error' => $errors->first('photo'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('js')
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
