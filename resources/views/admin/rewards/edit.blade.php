@extends('admin.master')

@section('title', 'RTL - Ubah Hadiah')

@section('content')
<div class="section-header">
  <h1>Ubah Hadiah</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.rewards.index') }}">Hadiah</a></div>
    <div class="breadcrumb-item">Ubah Hadiah</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Hadiah</h2>
  <p class="section-lead">
    Form untuk ubah hadiah
  </p>

  <div class="card">
    <form action="{{ route('admin.rewards.update', ['id' => $reward->id]) }}" method="POST" enctype="multipart/form-data">
      <div class="card-header">
        <h4>Ubah Hadiah</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Judul',
                    'type' => 'text',
                    'name' => 'title',
                    'required' => TRUE,
                    'value' => $reward->title,
                    'error' => $errors->first('title'),
                ])
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Target Poin',
                    'type' => 'text',
                    'name' => 'target_point',
                    'required' => TRUE,
                    'value' =>  $reward->target_point,
                    'error' => $errors->first('target_point'),
                ])
                @endcomponent
            </div>
        </div>
        @component('admin.components.form-input', [
            'label' => 'Deskripsi',
            'type' => 'textarea',
            'name' => 'description',
            'value' =>  $reward->description,
            'class' => 'tinymce',
            'required' => TRUE,
            'error' => $errors->first('description'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Foto',
            'type' => 'image',
            'name' => 'photo',
            'value' =>  url('uploads/rewards/' . $reward->photo),
            'error' => $errors->first('photo'),
        ])
        @endcomponent
      </div>
      <div class="card-footer text-right">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script>
new Cleave('input[name=target_point]', {
  numeral: true,
  delimiter: ',',
});

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
