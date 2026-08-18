@extends('admin.master')

@section('title', 'RTL - Ubah Lookbook')

@section('content')
<div class="section-header">
  <h1>Ubah Lookbook</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.lookbooks.index') }}">Lookbook</a></div>
    <div class="breadcrumb-item">Ubah Lookbook</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Lookbook</h2>
  <p class="section-lead">
    Form untuk tambah lookbook
  </p>

  <form action="{{ route('admin.lookbooks.update', ['id' => $lookbook->id]) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Ubah Lookbook</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Nama',
                'type' => 'text',
                'name' => 'name',
                'value' => $lookbook->name,
                'required' => TRUE,
                'error' => $errors->first('name'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Tanggal',
                'type' => 'text',
                'name' => 'date',
                'value' => $lookbook->date,
                'class' => 'datepicker',
                'error' => $errors->first('date'),
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
                'label' => 'Aktif',
                'type' => 'select',
                'name' => 'is_active',
                'value' => $lookbook->is_active,
                'options' => [
                    0 => 'Tidak',
                    1 => 'Ya',
                ],
                'error' => $errors->first('is_active'),
                'required' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Cover',
                'type' => 'image',
                'name' => 'cover_photo',
                'value' => url('uploads/lookbooks/' . $lookbook->cover_photo),
                'required' => TRUE,
                'error' => $errors->first('cover_photo'),
            ])
            @endcomponent
            <div id="input-photo">
            @component('admin.components.form-input', [
                'label' => 'Foto',
                'type' => 'images',
                'name' => 'photos',
                'value' => $photos,
                'required' => TRUE,
                'error' => $errors->first('photos'),
            ])
            @endcomponent
            </div>
            <div id="spinner-photo" style="display:none;margin-top:16px;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('js')
<script>
$(".image-list__item__close").on("click", function(event) {
    event.stopPropagation();

    var $this = $(this);
    var index = $this.parent().data('index');

    if (index === undefined) {
        $this.parent().remove();
    }

    swal({
      title: 'Apa anda yakin?',
      text: 'Foto ini akan dihapus secara permanen.',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then(function(willDelete) {
      if (willDelete) {
        $('#spinner-photo').show();
        $('#input-photo').hide();

        $.ajax({
            method: "DELETE",
            url: "{{ route('admin.lookbooks.deletePhoto', ['id' => $lookbook->id]) }}",
            data: {
                index: index,
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                $('#spinner-photo').hide();
                $('#input-photo').show();

                iziToast.success({
                    title: 'Berhasil!',
                    message: 'Berhasil hapus foto lookbook',
                    position: 'topRight'
                });

                $this.parent().remove();
            },
            error: function() {
                $('#spinner-photo').hide();
                $('#input-photo').show();

                iziToast.error({
                    title: 'Gagal!',
                    message: 'Gagal hapus foto lookbook',
                    position: 'topRight'
                });
            }
        });
      }
    });
});
</script>
@endsection
