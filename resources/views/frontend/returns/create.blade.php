@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table">
          <div class="sub-title">
            Buat Pengembalian
          </div>
          <form method="POST" action="{{ route('frontend.return.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <select name="sales_id" id="sales" class="form-control">
                <option value="">Pilih Kode Pemesanan</option>
                @foreach ($sales as $item)
                <option value="{{ $item->id }}">{{ $item->code }}</option>
                @endforeach
              </select>
              <label class="norm-label">Kode Pemesanan</label>
              @if ($errors->first('sales_id'))
                <div class="invalid-feedback" style="display:block;position:absolute;top:42px;">{{ $errors->first('sales_id') }}</div>
              @endif
            </div>
            <div class="form-group">
              <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Catatan"></textarea>
              <label class="norm-label">Catatan</label>
            </div>
            <div class="form-group">
                <label class="norm-label" style="opacity:1;left:0px;">Foto Bukti (maks. 5)</label>
                <div class="d-flex flex-wrap" style="margin-top: 24px;">
                    @foreach(range(1, 5) as $num)
                    <div class="image-upload">
                        <i class="fa fa-camera"></i>
                        <input type="file" name="photos[]" />
                    </div>
                    @endforeach
                    @if ($errors->first('photos'))
                    <div class="invalid-feedback" style="display: block;">{{ $errors->first('photos') }}</div>
                    @endif
                </div>
            </div>
            <div class="dashed-title">
              <span>Produk</span>
            </div>
            <div id="spinner" style="display:none;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin:32px;">
                </div>
            </div>
            <div class="cart-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Warna</th>
                            <th>Kuantitas</th>
                            <th>Berat</th>
                            <th>Harga Satuan</th>
                            <th>Total harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <input type="submit" class="btn btn-main" style="width: 128px; margin-left: auto;" value="Simpan">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
var $imageUpload = $(".image-upload");
var $sales = $("#sales");
var $table = $(".table");

$imageUpload.on("click", function() {
    $(this)
        .find("input")
        .click();
});

$imageUpload.find("input").on("click", function(event) {
    event.stopPropagation();
});

$imageUpload.find("input").on("change", function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        var $item = $(this).parent();
        var $icon = $item.find("i");
        var size = this.files[0].size;
        var isSizeExceeded = size > 1024000;

        if (isSizeExceeded) {
            alert("Maaf, ukuran foto max. 1 MB !");
            return;
        }

        reader.onload = function(event) {
            var result = event.target.result;
            var image = '<img src="' + result + '" />';
            var hasImage = !!$item.find("img").length;

            if (hasImage) {
                $item.find("img").remove();
            }

            $icon.remove();
            $item.append(image);
        };

        reader.readAsDataURL(this.files[0]);
    }
});

$sales.on('change', function() {
    var id = $(this).val();

    if (!id) {
        $table.find('tbody').empty();
        return;
    }

    $('#spinner').show();
    $('.cart-table').hide();

    $.ajax({
        method: "GET",
        url: "{{ url('ajax/order') }}/" + id,
        success: function(response) {
            $('#spinner').hide();
            $('.cart-table').show();

            var data = response.data;
            var items = data.details_by_index;

            var totalAmount = items.reduce((acc, item) => {
                var subtotal = item.colors.reduce((acc1, color) => {
                    return acc1 + color.subtotal;
                }, 0);

                return acc + subtotal;
            }, 0);

            items.forEach(function(item, index) {
                var colors = item.colors;

                var colWarna = colors
                    .map((color, colorIndex) => `
                        <div class="warna-box">
                            <div class="warna" style="background-color: ${color.hex_code}"></div>
                            <div>
                                <span>${color.name}</span><br>
                                <span style="font-size: 14px;color: #888;">Tersisa <span class="remaining-quantity">0</span> stok</span>
                            </div>
                            <input type="hidden" name="items[${index}][colors][${colorIndex}][sales_detail_id]" value="${color.sales_detail_id}" />
                            <input type="hidden" name="items[${index}][colors][${colorIndex}][color_id]" value="${color.color_id}" />
                        </div>
                    `)
                    .join('');

                var colQty = colors
                    .map((color, colorIndex) => `
                        <div class="qty-box">
                            <div class="input-quantity">
                                <button type="button" name="decrease">-</button>
                                <input class="no-spinner" name="items[${index}][colors][${colorIndex}][quantity]" type="number" min="0" max="${color.quantity}" step="1" value="${color.quantity}">
                                <button type="button" name="increase">+</button>
                            </div>
                        </div>
                    `)
                    .join('');

                var colBerat = colors
                    .map((color, colorIndex) => `
                        <div class="berat-box">
                            <span class="jumlah-berat">${item.weight * color.quantity}</span> gram
                            <input type="hidden" class="input-total-weight" name="items[${index}][colors][${colorIndex}][total_weight]" value="${item.weight * color.quantity}" />
                        </div>
                    `)
                    .join('');

                var colHargaSatuan = colors
                    .map(color => `
                        <div class="harga-box">
                            <span>Rp.</span>
                            <span class="ml-auto harga-satuan">${item.price_used}</span>
                        </div>
                    `)
                    .join('');

                var colTotalHarga = colors
                    .map((color, colorIndex) => `
                        <div class="harga-box">
                            <span>Rp.</span>
                            <span class="ml-auto total-jumlah">${color.subtotal}</span>
                            <input type="hidden" class="input-subtotal" name="items[${index}][colors][${colorIndex}][subtotal]" value="${color.subtotal}" />
                        </div>
                    `)
                    .join('');

                var cssClass = item.type;
                var typeLabel = cssClass[0].toUpperCase() + cssClass.slice(1).toLowerCase();

                if (item.type === 'normal') {
                    cssClass = 'ecer';
                    typeLabel = 'Ecer';
                } else if (item.type === 'wholesaler') {
                    cssClass = 'reseller';
                    typeLabel = 'Grosir';
                }

                var row = `
                    <tr>
                        <td>
                            <div class="col-product">
                                <div class="img-box">
                                    <img src="${item.product_photos[0]}">
                                </div>
                                <div class="text-box">
                                    <div class="tipe-beli ${cssClass}">
                                        ${typeLabel}
                                    </div>
                                    <div class="nama">
                                        <p>${item.product_name}</p>
                                    </div>
                                    <div class="berat">
                                        Berat /pcs : <span class="nilai-berat">${item.weight}</span> gram
                                    </div>
                                </div>
                                <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}" />
                            </div>
                        </td>
                        <td class="col-warna">${colWarna}</td>
                        <td class="col-qty">${colQty}</td>
                        <td class="col-berat">${colBerat}</td>
                        <td class="col-harga-satuan">${colHargaSatuan}</td>
                        <td class="col-total-harga">${colTotalHarga}</td>
                    </tr>
                `;

                $table.find('tbody').append(row);
            });

            var footer = `
                <tr>
                    <td colspan="5"><p class="text-right" style="margin:0px;">Total Nominal</p></td>
                    <td><p id="total-amount" class="text-right" style="margin:0px;white-space:nowrap;">Rp. ${totalAmount}</p></td>
                </tr>
            `;

            $table.find('tbody').append(footer);
        },
        error: function() {
            $('#spinner').hide();
            $('.cart-table').show();

            $table.find('tbody').empty();

            swal({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal mengambil items',
            });
        }
    });
});

$table.find('tbody').on('change', '.input-quantity input', function() {
    var $row = $(this).parents('tr');

    var quantity = $(this).val();
    var maxQuantity = Number($(this).attr('max'));
    var index = $(this).parents('.qty-box').index();
    var weight = Number($row.find('.nilai-berat').text());
    var price = Number($row.find('.harga-satuan:eq(0)').text());

    if (quantity < 0) {
        quantity = 0;

        $(this).val(0);
    }

    if (quantity > maxQuantity) {
        quantity = maxQuantity;

        $(this).val(maxQuantity);
    }

    $row.find('.warna-box').eq(index).find('.remaining-quantity').text(maxQuantity - quantity);
    $row.find('.berat-box').eq(index).find('.jumlah-berat').text(weight * quantity);
    $row.find('.berat-box').eq(index).find('.input-total-weight').val(weight * quantity);
    $row.find('.col-total-harga .harga-box').eq(index).find('.total-jumlah').text(price * quantity);
    $row.find('.col-total-harga .harga-box').eq(index).find('.input-subtotal').val(price * quantity);

    var totalAmount = 0;

    $('.total-jumlah').each(function() {
        totalAmount += Number($(this).text());
    });

    $('#total-amount').text('Rp. ' + totalAmount);
});

$table.find('tbody').on('click', '.input-quantity button', function() {
    var $row = $(this).parents('tr');
    var $input = $(this).parents('.input-quantity').find('input');

    var name = $(this).attr('name');
    var incrementor = name === 'increase' ? 1 : -1;
    var oldValue = Number($input.val());
    var quantity = oldValue + incrementor;
    var maxQuantity = Number($input.attr('max'));
    var index = $(this).parents('.qty-box').index();
    var weight = Number($row.find('.nilai-berat').text());
    var price = Number($row.find('.harga-satuan:eq(0)').text());

    if (quantity < 0 || quantity > maxQuantity) return;

    $input.val(quantity);
    $row.find('.warna-box').eq(index).find('.remaining-quantity').text(maxQuantity - quantity);
    $row.find('.berat-box').eq(index).find('.jumlah-berat').text(weight * quantity);
    $row.find('.berat-box').eq(index).find('.input-total-weight').val(weight * quantity);
    $row.find('.col-total-harga .harga-box').eq(index).find('.total-jumlah').text(price * quantity);
    $row.find('.col-total-harga .harga-box').eq(index).find('.input-subtotal').val(price * quantity);

    var totalAmount = 0;

    $('.total-jumlah').each(function() {
        totalAmount += Number($(this).text());
    });

    $('#total-amount').text('Rp. ' + totalAmount);
});
</script>
@endsection
