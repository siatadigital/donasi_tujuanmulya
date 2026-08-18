@extends('frontend.master')

@section('content')
<div class="product-list-main">
  <div class="container">
    @if (Route::currentRouteName() == "frontend.product.list.lookbook")
      <div style="margin-bottom: 20px;">
      @foreach(explode("|", $currentLookbook->photos) as $item)
        <img src="{{ url('uploads/lookbooks/'.$item) }}" width="100%" />
      @endforeach
      </div>
    @endif
    <form method="get" action="">
      <div class="row">
        <div class="col-lg-3 col-md-4 d-sm-none d-md-block">
          <h5 class="filter-head">Filter</h5>
          @foreach($categories as $item)
            <div class="filter-nav">
              <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseCategory{{ $item->id }}" aria-expanded="false" aria-controls="collapseExample">
                <span>{{ $item->name }}</span>
                <i class="fas fa-plus"></i>
                <i class="fas fa-minus"></i>
              </button>
              <div class="choses no-circle collapse" id="collapseCategory{{ $item->id }}">
                @foreach($item->childs as $itemChild)
                  <div class="chose custom-control custom-radio">
                    <input
                        type="radio"
                        id="custom-radio-category-{{ $itemChild->id }}"
                        name="category"
                        value="{{ $itemChild->id }}"
                        class="custom-control-input"
                        data-slug="{{ $itemChild->slug }}"
                        {{ $itemChild->id == $category ? 'checked' : '' }}
                    >
                    <label class="custom-control-label" for="custom-radio-category-{{ $itemChild->id }}">{{ $itemChild->name }}</label>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
          <div class="filter-nav">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#warna" aria-expanded="false" aria-controls="collapseExample">
              <span>Warna</span>
              <i class="fas fa-plus"></i>
              <i class="fas fa-minus"></i>
            </button>
            <div class="choses warna collapse" id="warna">
              @foreach($colors as $item)
              <div class="chose custom-control custom-radio">
                <style>#custom-radio-color-{{ $item->id }}+label:before { background-color: {{ $item->hex_code }};}</style>
                <input
                    type="checkbox"
                    id="custom-radio-color-{{ $item->id }}"
                    name="colors"
                    value="{{ $item->id }}"
                    class="custom-control-input"
                    {{ in_array($item->id, $colorIds) ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="custom-radio-color-{{ $item->id }}">{{ $item->name }}</label>
              </div>
              @endforeach
            </div>
          </div>
          <div class="filter-nav">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#price" aria-expanded="false" aria-controls="collapseExample">
              <span>Harga</span>
              <i class="fas fa-plus"></i>
              <i class="fas fa-minus"></i>
            </button>
            <div class="price-range collapse" id="price">
              <div class="form-group">
                <input type="number" id="min-price" name="min-price" class="form-control price"
                placeholder="Harga Minimal" value="{{ $minPrice }}">
                <label class="norm-label">Harga Minimal</label>
                <span>Rp</span>
              </div>
              <div class="form-group">
                <input type="number" id="max-price" name="max-price" class="form-control price"
                placeholder="Harga Maksimal" value="{{ $maxPrice }}">
                <label class="norm-label">Harga Maksimal</label>
                <span>Rp</span>
              </div>
            </div>
          </div>
          <div class="filter-nav">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseStock" aria-expanded="false" aria-controls="collapseExample">
              <span>Ketersediaan</span>
              <i class="fas fa-plus"></i>
              <i class="fas fa-minus"></i>
            </button>
            <div class="choses no-circle collapse" id="collapseStock">
                <div class="chose custom-control custom-radio">
                  <input
                      type="radio"
                      id="custom-radio-stock-1"
                      name="stock"
                      value="all"
                      class="custom-control-input"
                      {{ !$stock || $stock === 'all' ? 'checked' : '' }}
                  >
                  <label class="custom-control-label" for="custom-radio-stock-1">Semua</label>
                </div>
                <div class="chose custom-control custom-radio">
                  <input
                      type="radio"
                      id="custom-radio-stock-2"
                      name="stock"
                      value="available"
                      class="custom-control-input"
                      {{ $stock === 'available' ? 'checked' : '' }}
                  >
                  <label class="custom-control-label" for="custom-radio-stock-2">Tersedia</label>
                </div>
                <div class="chose custom-control custom-radio">
                  <input
                      type="radio"
                      id="custom-radio-stock-3"
                      name="stock"
                      value="empty"
                      class="custom-control-input"
                      {{ $stock === 'empty' ? 'checked' : '' }}
                  >
                  <label class="custom-control-label" for="custom-radio-stock-3">Habis</label>
                </div>
            </div>
          </div>
          <button id="btn-filter" type="button" class="btn btn-main" style="margin-top:32px;">Terapkan</button>
        </div>
        <div class="col-lg-9 col-md-8">
          <div class="semua-produk-head">
            <div class="row">
              <div class="col">
                @if (isset(request()->search))
                  <span class="head">Hasil pencarian "{{ request()->search }}"</span>
                @else
                  @if (Route::currentRouteName() == "frontend.product.list.category")
                    <span class="head">Produk pada Kategori "{{ $currentCategory->name }}"</span>
                  @elseif (Route::currentRouteName() == "frontend.product.list.lookbook")
                    <span class="head">Produk pada Lookbook "{{ $currentLookbook->name }}"</span>
                  @else
                    <span class="head">Semua Produk</span>
                  @endif
                @endif
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
          </div>
          <div class="semua-produk-main">
            @if (count($products) == 0)
              Tidak ada produk ditemukan
            @endif
            <div class="row">
              @foreach($products as $item)
                @php
                  $photo = $item->photos ? explode('|', $item->photos)[0] : NULL;
                  $isExists = $photo && file_exists(public_path('uploads/products/small/' . $photo));
                  $filename = $isExists ? $photo : 'default.png';
                  $src = url('uploads/products/small/' . $filename);
                @endphp
                <div class="col-lg-4 col-md-6 col-6">
                  <a href="{{ route('frontend.product.detail', array('slug' => $item->slug)) }}" class="produk-box">
                    <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;">
                      @if($item->current_total_stock_warehouse == 0)
                        @if($item->is_open_preorder)
                          <div class="status-produk open-po">Open Po</div>
                        @else
                          <div class="status-produk stok-habis">Stok Habis</div>
                        @endif
                      @else
                        @if($item->discount)
                          <span class="promo">PROMO!</span>
                        @endif
                      @endif
                    </div>
                    <div class="text-box">
                      <div class="name">{{ $item->title }}</div>
                      @if($item->discount)
                        <div>
                          <span class="price-promo">Rp. {{ number_format($item->price_sell_normal) }}</span>
                          <span class="price">Rp. {{ number_format($item->price_sell_normal - $item->discount) }}</span>
                        </div>
                      @else
                        <div>
                          <span class="price">Rp. {{ number_format($item->price_sell_normal) }}</span>
                        </div>
                      @endif
                    </div>
                    <div class="color-box">
                      <span>Tersedia dalam {{ count($item->productColors) }} warna</span>
                      <div class="color-items">
                        @foreach($item->productColors as $itemColor)
                          <div style="background-color: {{ $itemColor->color ? $itemColor->color->hex_code : '#ccc' }}"></div>
                        @endforeach
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
          <nav class="semua-produk-pagin">
            {{ $products->render() }}
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
    var isProductCategory = url.includes('products/category');
    var categorySlug = $('input[name=category]:checked').data('slug');

    var form = $(this)
        .parents('form')
        .serializeArray()
        .filter(item => {
            var isNotColors = item.name !== 'colors';
            var isNotEmpty = item.value !== "";
            var isValid = isNotColors && isNotEmpty;

            if (isProductCategory) {
                isValid = isValid && item.name !== 'category';
            }

            return isValid;
        });

    var colorIds = $(this)
        .parents('form')
        .serializeArray()
        .filter(item => item.name === 'colors')
        .map(item => item.value);

    var params = [{ name: 'colors', value: colorIds }]
        .concat(form)
        .map(item => {
            var isValueArray = Array.isArray(item.value);
            var value = isValueArray ? item.value.join(',') : item.value;

            return `${item.name}=${value}`;
        })
        .join('&');

    if (isProductCategory) {
        window.location = `{{ route('frontend.product.list.category', ['slugCategory' => '']) }}/${categorySlug}?${params}`;
    } else {
        window.location = `{{ Request::url() }}?${params}`;
    }
}

$('#sort-by').on('change', applyUpdates);
$('#btn-filter').on('click', applyUpdates);
</script>
@endsection
