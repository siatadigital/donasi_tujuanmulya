@extends('frontend.master')
@php
  $photo = $product->photos ? explode('|', $product->photos)[0] : NULL;
  $isExists = $photo && file_exists(public_path('uploads/products/small/' . $photo));
  $filename = $isExists ? $photo : 'default.png';
  $srcMain = url('uploads/products/small/' . $filename);
@endphp

@section('meta')
<title>{{ $settings['WEB_TITLE'] }} | {{ $product->title }}</title>
<meta name="description" content="{{ substr(strip_tags($product->description), 0, 250) }}">

<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $settings['WEB_TITLE'] }} | {{ $product->title }}" />
<meta property="og:site_name" content="{{ $settings['WEB_TITLE'] }} | {{ $product->title }}">
<meta property="og:description" content="{{ substr(strip_tags($product->description), 0, 250) }}" />
<meta property="og:image" itemprop="image" content="{{ $srcMain }}" />
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">
@endsection

@section('content')
<div class="container">
  <div class="product-detail-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
            <li
                class="breadcrumb-item {{ $breadcrumb->is_last ? 'active' : '' }}"
                {{ $breadcrumb->is_last ? 'aria-current="page"' : '' }}
            >
                <a {!! !$breadcrumb->is_last ? "href=\"{$breadcrumb->link}\"" : '' !!}>
                    {{ $breadcrumb->label }}
                </a>
            </li>
            @endforeach
        </ol>
    </nav>
  </div>

  <div class="product-detail-main">
    <div class="row">
      <div class="col-lg-5 col-md-6">
        <div class="product-detail-preview">
          <img id="main-img-prev" class="main-prev" src="{{ $srcMain }}">
          <div class="img-prev-slider">
            <div id="img-prev-slider" class="owl-carousel owl-theme">
              @php
                $photoSlide = $product->photos ? explode('|', $product->photos) : [];
              @endphp
              @foreach($photoSlide as $item)
                @php
                  $photo = $item;
                  $isExists = $photo && file_exists(public_path('uploads/products/small/' . $photo));
                  $filename = $isExists ? $photo : 'default.png';
                  $src = url('uploads/products/small/' . $filename);
                @endphp
                <div class="product-item">
                    <a href="" role="button" class="item img-change">
                        <img src="{{ $src }}">
                    </a>
                </div>
              @endforeach
            </div>
            @if (count($photoSlide) > 0)
              <button id="slider-prevBtn" role="presentation" class="slider-prevBtn">
                <i class="fas fa-angle-left"></i>
              </button>
              <button id="slider-nextBtn" role="presentation" class="slider-nextBtn">
                <i class="fas fa-angle-right"></i>
              </button>
            @endif
          </div>
          <br>
          <a href="{{ route('frontend.product.detail_download', ['slug' => $product->slug]) }}" class="btn btn-info">Unduh Gambar</a>
        </div>
      </div>
      <div class="col-lg-7 col-md-6">
        <h4 class="product-detail-name" style="margin-bottom:8px;">{{ $product->title }}</h4>
        <p style="font-size:14px;">
            <span style="margin-right: 32px;">Total Stok Gudang: {{ number_format($product->current_total_stock_warehouse) }} pcs</span>
            <span style="margin-right: 32px;">Total Stok Toko: {{ number_format($product->current_total_stock_store) }} pcs</span>
            <span>Total Stok Shopee: {{ number_format($product->current_total_stock_shopee) }} pcs</span>
        </p>

        <div class="product-detail-price">
          <span id="price-main" class="promo">Rp. {{ number_format($product->price_sell_normal - $product->normal_discount_amount) }}</span>
          <span id="price-2nd" class=" normal">Rp. {{ number_format($product->price_sell_seri) }}</span>
        </div>
        <form method="post" action="{{ route('ajax.product.buynow', array('slug' => $product->slug)) }}" id="product-detail-form">
          {{ csrf_field() }}
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          @if($product->current_total_stock_warehouse <= 0)
            @if($product->is_open_preorder)
              <div class="product-detail-status open-po">
                <div class="">
                  <h4>Open PO!</h4>
                  <p>Silahkan menghubungi Admin untuk informasi lebih lanjut</p>
                  <a href="" class="btn">Saya Ingin Pre-order</a>
                </div>
              </div>
            @else
              <div class="product-detail-status stok-habis">
                <div class="">STOK HABIS</div>
              </div>
            @endif
          @endif
          <div class="product-detail-info">
            <h5 class="head">Harga Reseller</h5>
            <p style="margin-bottom: 0px;">Beli minimal 4 pcs : Rp. {{ number_format($product->price_sell_reseller) }}</p>
          </div>
          <div class="product-detail-info">
            <h5 class="head">Harga Seri</h5>
            <p style="margin-bottom: 0px;">Beli 6 pcs pada produk ini dengan berbagai warna : Rp. {{ number_format($product->price_sell_seri) }}</p>
          </div>
          <div class="product-detail-info">
            <h5 class="head">Harga Grosir</h5>
            <p style="margin-bottom: 0px;">Beli 50 pcs : Rp. {{ number_format($product->price_sell_wholesaler_50) }}</p>
            <p style="margin-bottom: 0px;">Beli 100 pcs : Rp. {{ number_format($product->price_sell_wholesaler_100) }}</p>
            <p style="margin-bottom: 0px;">Beli 200 pcs : Rp. {{ number_format($product->price_sell_wholesaler_200) }}</p>
            <p style="margin-bottom: 0px;">Beli 400 pcs : Rp. {{ number_format($product->price_sell_wholesaler_400) }}</p>
            <p style="margin-bottom: 0px;">Beli 600 pcs : Rp. {{ number_format($product->price_sell_wholesaler_600) }}</p>
          </div>
          <div class="product-detail-info">
            <h5 class="head">Beli dengan</h5>
            <div id="tipe-beli" class="main tipe-beli">
              <div class="form-check">
                <input class="form-check-input ecer" type="radio" name="tipe_beli" id="tipe-beli1" value="ecer" checked>
                <label class="form-check-label" for="tipe-beli1">
                  Ecer
                </label>
              </div>
              @if ($isSeriAllowed)
              <div class="form-check">
                <input class="form-check-input seri" type="radio" name="tipe_beli" id="tipe-beli2" value="seri">
                <label class="form-check-label" for="tipe-beli2">
                  Seri
                </label>
              </div>
              @endif
              <div id="ecer-info" class="tipe-beli-info ecer">
                *Pembelian akan dikenakan harga normal
              </div>
              <div id="seri-info" class="tipe-beli-info seri">
                *Anda akan mendapatkan potongan sebesar Rp. {{ number_format($product->price_sell_normal - $product->price_sell_seri) }}/pcs dengan minimum pembelian 6 pcs
              </div>
            </div>
          </div>
          <div class="product-detail-info">
            <div id="pilihan-warna1">
                <h5 class="head">Pilih Warna Tersedia</h5>
                <div class="main pilih-warna">
                    @if(count($product->productColors) <= 0)
                      Tidak ada warna tersedia
                    @endif
                    <div class="row">
                        @php
                        $index = 0;
                        @endphp
                        @foreach($product->productColors as $item)
                        <div class="col-6">
                            <div class="warna-box">
                                    <style>#warnaecer{{ $item->color_id }}+label:before { background-color: {{ $item->color ? $item->color->hex_code : '#ccc' }};}</style>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="warnaecer{{ $item->color_id }}" name="color_id" value="{{ $item->color_id }}" @if($index == 0) checked @endif required />
                                        <label class="custom-control-label hitam" for="warnaecer{{ $item->color_id }}">{{ $item->color ? $item->color->name : '#ccc' }} ({{ $item->current_total_stock_warehouse }})</label>
                            </div>
                            </div>
                        </div>
                        @php
                        $index++;
                        @endphp
                        @endforeach
                    </div>
                </div>
            </div>
            <div id="pilihan-warna2">
                @if (!$isShoes)
                <h5 class="head">Warna Tersedia</h5>
                <div class="main pilih-warna">
                    @if(count($product->productColors) <= 0)
                      Tidak ada warna tersedia
                      <br/>
                      <br/>
                    @endif
                    <div class="row">
                        @php
                        $index = 0;
                        @endphp
                        @foreach($product->productColors as $item)
                        <div class="col-6">
                            <div class="warna-box">
                            <style>#warnaecer{{ $item->color_id }}+label:before { background-color: {{ $item->color ? $item->color->hex_code : '#ccc' }};}</style>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="warnaecer{{ $item->color_id }}" name="color_id" value="{{ $item->color_id }}" disabled />
                                <label class="custom-control-label hitam" for="warnaecer{{ $item->color_id }}">{{ $item->color ? $item->color->name : '#ccc' }} ({{ $item->current_total_stock_warehouse }})</label>
                            </div>
                            </div>
                        </div>
                        @php
                        $index++;
                        @endphp
                        @endforeach
                    </div>
                </div>
                <br>
                <h5 class="head">Jumlah Item</h5>
                <div class="pt_Quantity" id="seri-qty">
                    <input class="no-spinner" type="number" name="quantity" min="1" step="1" value="1" data-inc="1">
                </div>
                <br><br>
                <div id="spinner-pick-colors" style="display:none;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin:32px;">
                    </div>
                </div>
                <div id="selected-colors"></div>
                @else
                <h5 class="head">Pilih Warna Tersedia</h5>
                <div class="main pilih-warna">
                    @if(count($selectedColors) <= 0)
                      Tidak ada warna tersedia
                    @endif
                    <div class="row">
                        <?php $i = 0; ?>
                        @foreach($selectedColors as $index => $color)
                        <div class="col-6">
                            <div class="warna-box">
                                <style>#warnaseri{{ $color->id }}+label:before { background-color: {{ $color->code }};}</style>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="warnaseri{{ $color->id }}" name="seri_color_id" value="{{ $color->id }}" {{ !$i ? 'checked' : '' }} required />
                                    <label class="custom-control-label hitam" for="warnaseri{{ $color->id }}">{{ $color->label }}</label>
                                </div>
                            </div>
                        </div>
                        <?php $i++ ?>
                        @endforeach
                    </div>
                </div>
                <h5 class="head">Jumlah Item</h5>
                <div class="pt_Quantity" id="seri-qty">
                    <input class="no-spinner" type="number" name="quantity" min="1" step="1" value="1" data-inc="1">
                </div>
                <br><br>
                <div id="spinner-pick-colors" style="display:none;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin:32px;">
                    </div>
                </div>
                <div id="selected-colors"></div>
                @endif
            </div>
          </div>
          <div class="product-detail-info double">
            <div class="info-double info-qty">
              <h5 class="head">Kuantitas</h5>
              <div class="main">
                <div class="qty-box">
                  <div class="qtyLabel">Qty: </div>
                  <div class="pt_Quantity">
                    <input class="no-spinner" type="number" name="quantity" min="1" step="1" value="1" data-inc="1">
                  </div>
                </div>
              </div>
            </div>
            <div class="info-double">
              <h5 class="head">Estimasi Berat Produk</h5>
              <div class="main berat">
                <img src="{{ url('img/kilogram.png') }}">
                <span>{{ $product->weight }} g / Pcs</span>
              </div>
            </div>
          </div>
          @if ($errors->any())
              @foreach ($errors->all() as $error)
                  <div>{{$error}}</div>
              @endforeach
          @endif
          @if($product->current_total_stock_warehouse > 0)
            <div id="spinner" style="display:none;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin-bottom:48px;">
                </div>
            </div>
            @if (auth()->user())
              @if (auth()->user()->role == 'customer')
                <div class="product-detail-info buttons">
                  <a
                    class="btn addtocart"
                    href="#"
                    data-href="{{ route('ajax.product.addtocart', array('slug' => $product->slug)) }}"
                    data-has-deposit="{{ $hasDeposit }}"
                    data-has-cart="{{ $hasCart }}"
                  >
                    <i class="fas fa-shopping-bag"></i>
                    Masukkan keranjang
                  </a>
                  <input type="submit" class="btn" value="Beli Sekarang">
                </div>
              @endif
            @else
              <div class="product-detail-info buttons">
                <a
                  class="btn addtocart"
                  href="#"
                  data-href="{{ route('ajax.product.addtocart', array('slug' => $product->slug)) }}"
                  data-has-deposit="{{ $hasDeposit }}"
                  data-has-cart="{{ $hasCart }}"
                >
                  <i class="fas fa-shopping-bag"></i>
                  Masukkan keranjang
                </a>
                <input type="submit" class="btn" value="Beli Sekarang">
              </div>
            @endif
          @endif
          <div class="product-detail-info">
            <h5 class="head">Deskripsi Produk</h5>
            <div class="main">
              <p>
                {!! $product->description !!}
              </p>
            </div>
          </div>
          <div class="product-detail-share">
            <h5 class="head">Bagikan Kepada Teman</h5>
            <a target="_blank" href="https://www.facebook.com/sharer.php?u={{ route('frontend.product.detail', array('slug' => $product->slug)) }}"><img src="{{ url('img/facebook.png') }}"> Facebook</a>
            <a target="_blank" href="https://twitter.com/intent/tweet?text={{ $product->title }}&url={{ route('frontend.product.detail', array('slug' => $product->slug)) }}"><img src="{{ url('img/twitter.png') }}"> Twitter</a>
            <a target="_blank" href="https://api.whatsapp.com/send?text={{ $product->title }} {{ route('frontend.product.detail', array('slug' => $product->slug)) }}"><img src="{{ url('img/whatsapp.png') }}"> WhatsApp</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
  @if (session()->has('message'))
  <script>
  swal({
    icon: 'error',
    title: 'Gagal',
    text: '{{ session("message") }}',
  });
  </script>
  @endif

  <script>
    $(document).ready(function() {
      var $seriQty = $('#seri-qty');
      var $buyType = $('input[name=tipe_beli]');
      var $seriColorId = $('input[name=seri_color_id]');

      $seriQty.on('change', 'input', function() {
          var times = $(this).val();
          var isShoes = !!Number('{{ (int) $isShoes }}');
          var colorKey = isShoes ? $('input[name=seri_color_id]:checked').val() : undefined;

          $('#spinner-pick-colors').show();
          $('#selected-colors').hide();
          $('#selected-colors').empty();

          $.ajax({
            method: "GET",
            url: '{{ url("/ajax/{$product->slug}/seri-colors") }}',
            data: {
              times: times,
              color: colorKey,
            },
            success: function(response) {
              $('#spinner-pick-colors').hide();
              $('#selected-colors').show();

              var items = response.data;

              items.forEach((colors, index) => {
                var title = isShoes ? 'Ukuran Terpilih' : 'Warna Terpilih';

                var colorElements = colors.map((color, colorIndex) => {
                    return `
                        <div class="col-6">
                            <div class="warna-box">
                                <style>
                                    #warnaseri-${color.hash}+label:before {
                                        background-color: ${color.color_hex};
                                    }
                                </style>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        class="custom-control-input"
                                        type="checkbox"
                                        id="warnaseri-${color.hash}"
                                        name="color_id"
                                        value="${color.color_id}"
                                    />
                                    <label class="custom-control-label" for="warnaseri-${color.hash}">
                                        ${color.color} (${color.quantity})
                                    </label>
                                </div>
                                <input type="hidden" name="items[${index}][${colorIndex}][color_id]" value="${color.color_id}">
                                <input type="hidden" name="items[${index}][${colorIndex}][quantity]" value="${color.quantity}">
                            </div>
                        </div>
                    `;
                }).join('');

                var content = `
                    <h5 class="head">${title} (${index + 1})</h5>
                    <div>
                        <div class="row">
                            ${colorElements}
                        </div>
                    </div>
                    <br />
                `;

                $('#selected-colors').append(content);
              });
            },
            error: function() {
                $('#spinner-pick-colors').hide();
                $('#selected-colors').show();

                $('#selected-colors').append(`
                    <p>Maaf, Stok tidak mencukupi, silahkan kurangi jumlah item.</p>
                `);
            }
          });
      });

      $buyType.on('change', function() {
        var value = $(this).val();
        var isSeri = value === 'seri';

        if (isSeri) {
            $seriQty.find('input').change();
        }
      });

      $seriColorId.on('change', function() {
        var value = $(this).val();

        $seriQty.find('input').change();
      });

      $('a.btn.addtocart').click(function(){
        var url = $(this).data('href');

        var submit = function() {
            var data = $('#product-detail-form').serialize();

            $('#spinner').show();
            $('.product-detail-info.buttons').hide();

            $.ajax({
                method: "POST",
                url: url,
                data: data,
                success: function(cartItemCount) {
                    $('#spinner').hide();
                    $('.product-detail-info.buttons').show();
                    $('#btn-chart').html('<i class="fas fa-shopping-bag"></i> Keranjang (' + cartItemCount + ')');

                    $seriQty.find('input').change();

                    swal({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Berhasil tambahkan produk ke keranjang',
                    });
                },
                error: function(error) {
                    $('#spinner').hide();
                    $('.product-detail-info.buttons').show();

                    const message = error.status === 400 ? error.responseJSON.error : 'Gagal tambahkan produk ke keranjang';

                    swal({
                        icon: 'error',
                        title: 'Gagal',
                        text: message,
                    });
                }
            });
        };

        submit();

        return false;
      });
    });
  </script>
@endsection
