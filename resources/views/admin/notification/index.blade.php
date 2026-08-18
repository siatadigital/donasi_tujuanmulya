@extends('admin.master')

@section('title', 'RTL - Notifikasi')

@section('content')
<div class="section-header">
  <h1>Notifikasi</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Notifikasi</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Notifikasi</h2>
  <p class="section-lead">
    Form untuk mengirim notifikasi ke mobile app
  </p>

  <form action="{{ route('admin.notification.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Notifikasi</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Judul',
                'type' => 'text',
                'name' => 'title',
                'error' => $errors->first('title'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Pesan',
                'type' => 'text',
                'name' => 'body',
                'error' => $errors->first('body'),
            ])
            @endcomponent
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary">Kirim</button>
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
