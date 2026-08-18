@extends('admin.master')

@section('title', 'RTL - FAQs')

@section('content')
<div class="section-header">
  <h1>FAQs</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">FAQs</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">FAQs</h2>
  <p class="section-lead">
    Form untuk ubah konten di halaman FAQs
  </p>

  <form action="{{ route('admin.faq.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card question-item">
          <div class="card-header">
            <h4>FAQs (Pertanyaan 1)</h4>
          </div>
          <div class="card-body">
                @component('admin.components.form-input', [
                    'label' => 'Pertanyaan',
                    'type' => 'text',
                    'name' => 'questions[]',
                    'required' => TRUE,
                    'value' => explode('#?', $content[0])[0],
                    'error' => $errors->first('questions'),
                ])
                @endcomponent
                @component('admin.components.form-input', [
                    'label' => 'Jawaban',
                    'type' => 'textarea',
                    'name' => 'answers[]',
                    'value' => explode('#?', $content[0])[1],
                    'class' => 'tinymce',
                    'error' => $errors->first('answers'),
                ])
                @endcomponent
          </div>
          <div class="card-footer text-right">
            <button type="button" class="btn btn-success" id="btn-add">Tambah Pertanyaan</button>
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
        @foreach($content->slice(1) as $index => $item)
        <?php $part = explode('#?', $item) ?>
        <div class="card question-item">
            <div class="card-header">
                <h4>Pertanyaan {{ $index + 1 }}</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-danger btn-delete">Hapus</button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <input type="text" name="questions[]" class="form-control" value="{{ $part[0] }}" required />
                </div>
                <div class="form-group">
                    <label>Jawaban</label>
                    <textarea name="answers[]" class="form-control tinymce">
                    {{ $part[1] }}
                    </textarea>
                </div>
            </div>
        </div>
        @endforeach
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
$('#btn-add').on('click', function() {
    var index = $('.question-item').last().index() + 2;

    var $element = `
        <div class="card question-item">
            <div class="card-header">
                <h4>Pertanyaan ${index}</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-danger btn-delete">Hapus</button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <input type="text" name="questions[]" class="form-control" value="" required />
                </div>
                <div class="form-group">
                    <label>Jawaban</label>
                    <textarea name="answers[]" class="form-control tinymce">
                    </textarea>
                </div>
            </div>
        </div>
    `;

    $($element).insertAfter('.question-item:last');

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
});

$('.row').on('click', '.btn-delete', function() {
    var $questionItem = $(this).parents('.question-item');
    var index = $questionItem.index();
    var totalItems = $('.question-item').length;

    $questionItem.remove();

    for (let i = 0; i < totalItems - 1; i++) {
        var newIndex = i + 1;

        $('.question-item')
            .eq(i + 1)
            .find('h4')
            .text(`Pertanyaan ${i + 2}`);
    }
});
</script>
@endsection
