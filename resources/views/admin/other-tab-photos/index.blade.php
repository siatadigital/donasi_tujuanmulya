@extends('admin.master')

@section('title', 'RTL - Foto Tab Lainnya')

@section('content')
<div class="section-header">
  <h1>Foto Tab Lainnya</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Foto Tab Lainnya</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Foto Tab Lainnya</h2>
  <p class="section-lead">
    Form untuk ubah foto di tab Lainnya pada mobile app
  </p>

  <form action="{{ route('admin.other-tab-photos.store') }}" method="POST" enctype="multipart/form-data">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Foto Tab Lainnya</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Gambar',
                'type' => 'image',
                'name' => 'image',
                'value' => asset('uploads/others/' . $image),
                'required' => TRUE,
                'error' => $errors->first('image'),
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
@endsection
