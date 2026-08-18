@extends('admin.master')

@section('title', 'RTL - Tambah Pengiriman')

@section('content')
<div class="section-header">
  <h1>Tambah Pengiriman</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.deliveries.index') }}">Pengiriman</a></div>
    <div class="breadcrumb-item">Tambah Pengiriman</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Pengiriman</h2>
  <p class="section-lead">
    Form untuk tambah pengiriman
  </p>

  <form action="{{ route('admin.deliveries.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Pengiriman</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Penjualan',
                        'type' => 'select',
                        'name' => 'sales_id',
                        'options' => $sales,
                        'value' => old('sales_id'),
                        'error' => $errors->first('sales_id'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Tanggal',
                        'type' => 'text',
                        'name' => 'date',
                        'required' => TRUE,
                        'class' => 'datepicker',
                        'value' => old('date'),
                        'error' => $errors->first('date'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Bagian Pengecekan',
                        'type' => 'select',
                        'name' => 'user_checker_id',
                        'options' => $users,
                        'value' => old('user_checker_id'),
                        'error' => $errors->first('user_checker_id'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Bagian Ekspedisi',
                        'type' => 'select',
                        'name' => 'user_expedition_id',
                        'options' => $users,
                        'value' => old('user_expedition_id'),
                        'error' => $errors->first('user_expedition_id'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Kartu Anggota',
                'type' => 'select',
                'name' => 'order_cardmember_id',
                'options' => ['' => 'Pilih Kartu Anggota'],
                'value' => old('order_cardmember_id'),
                'error' => $errors->first('order_cardmember_id'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Nomor Ekspedisi',
                'type' => 'text',
                'name' => 'expedition_number',
                'value' => old('expedition_number'),
                'error' => $errors->first('expedition_number'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Catatan',
                'type' => 'textarea',
                'name' => 'notes',
                'value' => old('notes'),
                'error' => $errors->first('notes'),
            ])
            @endcomponent
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Asal Pengiriman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Nama Lengkap',
                                'type' => 'text',
                                'name' => 'origin_fullname',
                                'required' => TRUE,
                                'value' => old('origin_fullname'),
                                'error' => $errors->first('origin_fullname'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'No. Telp',
                                'type' => 'tel',
                                'name' => 'origin_phone',
                                'required' => TRUE,
                                'value' => old('origin_phone'),
                                'error' => $errors->first('origin_phone'),
                            ])
                            @endcomponent
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Alamat',
                                'type' => 'textarea',
                                'name' => 'origin_address',
                                'required' => TRUE,
                                'value' => old('origin_address'),
                                'error' => $errors->first('origin_address'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Kode Pos',
                                'type' => 'text',
                                'name' => 'origin_postcode',
                                'value' => old('origin_postcode'),
                                'error' => $errors->first('origin_postcode'),
                            ])
                            @endcomponent
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Provinsi',
                                'type' => 'select',
                                'name' => 'origin_province_id',
                                'required' => TRUE,
                                'options' => $provinces,
                                'value' => old('origin_province_id'),
                                'error' => $errors->first('origin_province_id'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Kota / Kabupaten',
                                'type' => 'select',
                                'name' => 'origin_city_id',
                                'required' => TRUE,
                                'options' => ['' => 'Pilih Kota / Kabupaten'],
                                'value' => old('origin_city_id'),
                                'error' => $errors->first('origin_city_id'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Kecamatan',
                                'type' => 'select',
                                'name' => 'origin_subdistrict_id',
                                'required' => TRUE,
                                'options' => ['' => 'Pilih Kecamatan'],
                                'value' => old('origin_subdistrict_id'),
                                'error' => $errors->first('origin_subdistrict_id'),
                            ])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Tujuan Pengiriman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Nama Lengkap',
                                'type' => 'text',
                                'name' => 'destination_fullname',
                                'required' => TRUE,
                                'value' => old('destination_fullname'),
                                'error' => $errors->first('destination_fullname'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'No. Telp',
                                'type' => 'tel',
                                'name' => 'destination_phone',
                                'required' => TRUE,
                                'value' => old('destination_phone'),
                                'error' => $errors->first('destination_phone'),
                            ])
                            @endcomponent
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Alamat',
                                'type' => 'textarea',
                                'name' => 'destination_address',
                                'required' => TRUE,
                                'value' => old('destination_address'),
                                'error' => $errors->first('destination_address'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('admin.components.form-input', [
                                'label' => 'Kode Pos',
                                'type' => 'text',
                                'name' => 'destination_postcode',
                                'value' => old('destination_postcode'),
                                'error' => $errors->first('destination_postcode'),
                            ])
                            @endcomponent
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Provinsi',
                                'type' => 'select',
                                'name' => 'destination_province_id',
                                'required' => TRUE,
                                'options' => $provinces,
                                'value' => old('destination_province_id'),
                                'error' => $errors->first('destination_province_id'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Kota / Kabupaten',
                                'type' => 'select',
                                'name' => 'destination_city_id',
                                'required' => TRUE,
                                'options' => ['' => 'Pilih Kota / Kabupaten'],
                                'value' => old('destination_city_id'),
                                'error' => $errors->first('destination_city_id'),
                            ])
                            @endcomponent
                        </div>
                        <div class="col-md-4">
                            @component('admin.components.form-input', [
                                'label' => 'Kecamatan',
                                'type' => 'select',
                                'name' => 'destination_subdistrict_id',
                                'required' => TRUE,
                                'options' => ['' => 'Pilih Kecamatan'],
                                'value' => old('destination_subdistrict_id'),
                                'error' => $errors->first('destination_subdistrict_id'),
                            ])
                            @endcomponent
                        </div>
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
var $originProvinces = $('select[name=origin_province_id]');
var $originCities = $('select[name=origin_city_id]');
var $originSubdistricts = $('select[name=origin_subdistrict_id]');
var $destinationProvinces = $('select[name=destination_province_id]');
var $destinationCities = $('select[name=destination_city_id]');
var $destinationSubdistricts = $('select[name=destination_subdistrict_id]');

var fetchCities = function($citiesElement) {
    return function() {
        var provinceId = $(this).val();

        $.ajax({
            method: "GET",
            url: "{{ route('admin.api.cities') }}?province_id=" + provinceId,
            success: function(response) {
                var cities = response.data;

                $citiesElement.empty();
                $citiesElement.append('<option value="">Pilih Kota / Kabupaten</option>');

                for (const city of cities) {
                    $citiesElement.append('<option value="' + city.id + '">' + city.name + '</option>');
                }
            },
            error: function() {
                iziToast.error({
                    title: 'Gagal!',
                    message: 'Gagal mengambil daftar kota / kabupaten',
                    position: 'topRight'
                });
            }
        });
    };
};

var fetchSubdistricts = function($subdistrictsElement) {
    return function() {
        var cityId = $(this).val();

        $.ajax({
            method: "GET",
            url: "{{ route('admin.api.subdistricts') }}?city_id=" + cityId,
            success: function(response) {
                var subdistricts = response.data;

                $subdistrictsElement.empty();
                $subdistrictsElement.append('<option value="">Pilih Kecamatan</option>');

                for (const subdistrict of subdistricts) {
                    $subdistrictsElement.append('<option value="' + subdistrict.id + '">' + subdistrict.name + '</option>');
                }
            },
            error: function() {
                iziToast.error({
                    title: 'Gagal!',
                    message: 'Gagal mengambil daftar kecamatan',
                    position: 'topRight'
                });
            }
        });
    };
};

$originProvinces.on('change', fetchCities($originCities));
$originCities.on('change', fetchSubdistricts($originSubdistricts));
$destinationProvinces.on('change', fetchCities($destinationCities));
$destinationCities.on('change', fetchSubdistricts($destinationSubdistricts));
</script>
@endsection
