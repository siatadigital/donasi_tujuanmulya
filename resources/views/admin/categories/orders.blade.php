@extends('admin.master')

@section('title', 'RTL - Urutkan Kategori')

@section('content')
<div class="section-header">
  <h1>Urutkan Kategori</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.categories.index') }}">Kategori</a></div>
    <div class="breadcrumb-item">Urutkan Kategori</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Urutkan Kategori</h2>
  <p class="section-lead">
    Form untuk urutkan kategori
  </p>

  <div class="card">
    <form action="{{ route('admin.categories.orders.update') }}" method="POST">
      <div class="card-header">
        <h4>Urutkan Kategori</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        <div id="sort-list">
            @foreach ($categories as $category)
            <div class="sort-item">
                <i class="fa fa-arrows-alt-v"></i>
                <p>{{ $category->name }}</p>
                <input type="hidden" class="input-category" name="categories[]" value="{{ $category->id }}">
                <input type="hidden" class="input-index" name="indexes[]" value="{{ $category->index }}">
            </div>
            @endforeach
        </div>
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
    var $sortList = $('#sort-list').get(0);

    new Sortable($sortList, {
        animation: 150,
        ghostClass: 'dragged',
        onEnd: function() {
            $('.sort-item').each(function(index) {
                $(this).find('.input-index').val(index);
            });
        },
    });
</script>
@endsection
