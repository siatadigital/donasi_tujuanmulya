@extends('admin.master')

@section('title', 'RTL - Tambah Produk')

@section('content')
<div class="section-header">
  <h1>Tambah Produk</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.products.index') }}">Produk</a></div>
    <div class="breadcrumb-item">Tambah Produk</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Produk</h2>
  <p class="section-lead">
    Form untuk tambah produk
  </p>

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Produk</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Judul',
                'type' => 'text',
                'name' => 'title',
                'value' => old('title'),
                'required' => TRUE,
                'error' => $errors->first('title'),
            ])
            @endcomponent
            <div class="row">
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Beli',
                        'type' => 'text',
                        'name' => 'price_buy',
                        'value' => old('price_buy'),
                        'error' => $errors->first('price_buy'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Normal)',
                        'type' => 'text',
                        'name' => 'price_sell_normal',
                        'value' => old('price_sell_normal'),
                        'error' => $errors->first('price_sell_normal'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Reseller)',
                        'type' => 'text',
                        'name' => 'price_sell_reseller',
                        'value' => old('price_sell_reseller'),
                        'error' => $errors->first('price_sell_reseller'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Seri)',
                        'type' => 'text',
                        'name' => 'price_sell_seri',
                        'value' => old('price_sell_seri'),
                        'error' => $errors->first('price_sell_seri'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Grosir 50 pcs)',
                        'type' => 'text',
                        'name' => 'price_sell_wholesaler_50',
                        'value' => old('price_sell_wholesaler_50'),
                        'error' => $errors->first('price_sell_wholesaler_50'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Grosir 100 pcs)',
                        'type' => 'text',
                        'name' => 'price_sell_wholesaler_100',
                        'value' => old('price_sell_wholesaler_100'),
                        'error' => $errors->first('price_sell_wholesaler_100'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Grosir 200 pcs)',
                        'type' => 'text',
                        'name' => 'price_sell_wholesaler_200',
                        'value' => old('price_sell_wholesaler_200'),
                        'error' => $errors->first('price_sell_wholesaler_200'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Grosir 400 pcs)',
                        'type' => 'text',
                        'name' => 'price_sell_wholesaler_400',
                        'value' => old('price_sell_wholesaler_400'),
                        'error' => $errors->first('price_sell_wholesaler_400'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Harga Jual (Grosir 600 pcs)',
                        'type' => 'text',
                        'name' => 'price_sell_wholesaler_600',
                        'value' => old('price_sell_wholesaler_600'),
                        'error' => $errors->first('price_sell_wholesaler_600'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Diskon (Opsional)',
                'type' => 'number',
                'name' => 'discount',
                'value' => old('discount'),
                'error' => $errors->first('discount'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Berat (gram)',
                'type' => 'text',
                'name' => 'weight',
                'value' => old('weight'),
                'required' => TRUE,
                'error' => $errors->first('weight'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Deskripsi',
                'type' => 'textarea',
                'name' => 'description',
                'value' => old('description'),
                'class' => 'tinymce',
                'required' => TRUE,
                'error' => $errors->first('description'),
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
                'label' => 'Publikasikan',
                'type' => 'select',
                'name' => 'is_published',
                'value' => old('is_published'),
                'options' => [
                    0 => 'Tidak',
                    1 => 'Ya',
                    2 => "Jadwal ($publishTime)",
                ],
                'error' => $errors->first('is_published'),
                'required' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Bisa Pre Order',
                'type' => 'select',
                'name' => 'is_open_preorder',
                'value' => old('is_open_preorder'),
                'options' => [
                    0 => 'Tidak',
                    1 => 'Ya',
                ],
                'error' => $errors->first('is_open_preorder'),
                'required' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Kategori (Opsional)',
                'type' => 'select',
                'name' => 'categories[]',
                'value' => old('categories'),
                'options' => $categories,
                'error' => $errors->first('categories'),
                'required' => FALSE,
                'multiple' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Lookbook (Opsional)',
                'type' => 'select',
                'name' => 'lookbooks[]',
                'value' => old('lookbooks'),
                'options' => $lookbooks,
                'error' => $errors->first('lookbooks'),
                'required' => FALSE,
                'multiple' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Warna (Opsional)',
                'type' => 'select',
                'name' => 'colors[]',
                'value' => old('colors'),
                'options' => $colors,
                'class' => 'colors',
                'error' => $errors->first('colors'),
                'required' => FALSE,
                'multiple' => TRUE,
                'additional' => [
                    'id' => 'colors'
                ]
            ])
            @endcomponent
            <div class="form-group">
                <label>Barcode</label>
                <div id="barcode-list"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('js')
@if (session()->has('error'))
<script>
swal({
    icon: 'error',
    title: 'Gagal',
    text: '{{ session("error") }}',
});
</script>
@endif
<script>
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

var cleaveConfig = {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
};

var colors = JSON.parse('{!! json_encode($colors) !!}');

new Cleave('input[name=price_buy]', cleaveConfig);
new Cleave('input[name=price_sell_normal]', cleaveConfig);
new Cleave('input[name=price_sell_reseller]', cleaveConfig);
new Cleave('input[name=price_sell_seri]', cleaveConfig);
new Cleave('input[name=price_sell_wholesaler_50]', cleaveConfig);
new Cleave('input[name=price_sell_wholesaler_100]', cleaveConfig);
new Cleave('input[name=price_sell_wholesaler_200]', cleaveConfig);
new Cleave('input[name=price_sell_wholesaler_400]', cleaveConfig);
new Cleave('input[name=price_sell_wholesaler_600]', cleaveConfig);
new Cleave('input[name=weight]', cleaveConfig);

$('#colors').on('change', function() {
    var colorIds = $(this).val();
    var items = [];

    for (const colorId of colorIds) {
        var name = colors[colorId];

        var item = `
            <div class="barcode-item">
                <p class="text-small" style="margin-bottom: 0px;">${name}</p>
                <input
                    type="text"
                    name="barcodes[]"
                    class="form-control"
                    placeholder="Isi barcode ${name} disini..."
                    value=""
                    required
                />
            </div>
            <br />
        `;

        items.push(item);
    }

    $('#barcode-list').empty().append(items);
});

$('#colors').change();

</script>
@endsection
