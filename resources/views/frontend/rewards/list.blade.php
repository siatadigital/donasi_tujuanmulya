@extends('frontend.master')

@section('content')
<div class="product-list-main">
  <div class="container">
    <form method="get" action="">
      <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="semua-produk-head">
            <div class="row">
              <div class="col">
                <span class="head">Semua Hadiah</span>
              </div>
              <div class="col urutkan">
                <span class="urutkan-label">Urutkan:</span>
                <div class="form-group">
                  <select id="sort-by" name="sort-by" class="form-control">
                    @foreach ($sorts as $sort)
                        @if ($sort->value === $sortBy)
                        <option value="{{ $sort->value }}" selected>{{ $sort->text }}</option>
                        @else
                        <option value="{{ $sort->value }}">{{ $sort->text }}</option>
                        @endif
                    @endforeach
                  </select>
                  <i class="select-icon fas fa-angle-down"></i>
                </div>
              </div>
            </div>
            @if (auth()->check())
            <p>Poin Saya : {{ auth()->user()->customer ? auth()->user()->customer->current_point_amount : 0 }} poin</p>
            @endif
          </div>
          <div class="semua-produk-main">
            @if (count($rewards) == 0)
              Tidak ada hadiah ditemukan
            @endif
            <div class="row">
              @foreach($rewards as $item)
                @php
                  $isExists = $item->photo && file_exists(public_path('uploads/rewards/thumb/' . $item->photo));
                  $filename = $isExists ? $item->photo : 'default.png';
                  $src = url('uploads/rewards/thumb/' . $filename);
                @endphp
                <div class="col-lg-3 col-md-6 col-6">
                  <a href="{{ route('frontend.reward.detail', array('slug' => $item->slug)) }}" class="produk-box">
                    <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
                    <div class="text-box">
                      <div class="name">{{ $item->title }}</div>
                      <div>
                        <span class="price">{{ number_format($item->target_point) }} Poin</span>
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
          <nav class="semua-produk-pagin">
            {{ $rewards->render() }}
          </nav>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script>
var applyUpdates = function(event) {
    event.preventDefault();

    var url = window.location.href;

    var params = $(this)
        .parents('form')
        .serializeArray()
        .map(item => {
            var isValueArray = Array.isArray(item.value);
            var value = isValueArray ? item.value.join(',') : item.value;

            return `${item.name}=${value}`;
        })
        .join('&');

    window.location = `{{ Request::url() }}?${params}`;
}

$('#sort-by').on('change', applyUpdates);
</script>
@endsection
