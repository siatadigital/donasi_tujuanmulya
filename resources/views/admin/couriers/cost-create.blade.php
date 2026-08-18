@extends('admin.master')

@section('title', 'RTL - Tambah Ongkos Kirim')

@section('content')
<div class="section-header">
  <h1>Tambah Ongkos Kirim</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.couriers.index') }}">Kurir</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.couriers.costs.index', ['id' => $id]) }}">Ongkos Kirim</a></div>
    <div class="breadcrumb-item">Tambah Ongkos Kirim</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Ongkos Kirim</h2>
  <p class="section-lead">
    Form untuk tambah ongkos kirim
  </p>

  <div class="card">
    <form action="{{ route('admin.couriers.costs.store', ['id' => $id]) }}" method="POST">
      <div class="card-header">
        <h4>Tambah Ongkos Kirim</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Provinsi',
                    'type' => 'select',
                    'name' => 'province_id',
                    'required' => TRUE,
                    'options' => $provinces,
                    'value' => old('province_id'),
                    'error' => $errors->first('province_id'),
                ])
                @endcomponent
            </div>
            <div class="col-md-4">
                <div id="input-city">
                @component('admin.components.form-input', [
                    'label' => 'Kota/Kabupaten',
                    'type' => 'select',
                    'name' => 'city_id',
                    'required' => TRUE,
                    'options' => ['' => 'Pilih Kota/Kabupaten'],
                    'value' => old('city_id'),
                    'error' => $errors->first('city_id'),
                ])
                @endcomponent
                </div>
                <div id="spinner-city" style="display:none;margin-top:16px;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="input-subdistrict">
                @component('admin.components.form-input', [
                    'label' => 'Kecamatan',
                    'type' => 'select',
                    'name' => 'subdistrict_id',
                    'required' => TRUE,
                    'options' => ['' => 'Pilih Kecamatan'],
                    'value' => old('subdistrict_id'),
                    'error' => $errors->first('subdistrict_id'),
                ])
                @endcomponent
                </div>
                <div id="spinner-subdistrict" style="display:none;margin-top:16px;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Biaya / kg',
                    'type' => 'text',
                    'name' => 'cost_by_kg',
                    'required' => TRUE,
                    'value' => old('cost_by_kg'),
                    'error' => $errors->first('cost_by_kg'),
                ])
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Berat Min (kg)',
                    'type' => 'text',
                    'name' => 'min_kg',
                    'required' => TRUE,
                    'value' => old('min_kg'),
                    'error' => $errors->first('min_kg'),
                ])
                @endcomponent
            </div>
        </div>
        @component('admin.components.form-input', [
            'label' => 'Deskripsi',
            'type' => 'textarea',
            'name' => 'description',
            'required' => TRUE,
            'value' => old('description'),
            'error' => $errors->first('description'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Estimasi',
            'type' => 'text',
            'name' => 'estimated',
            'required' => TRUE,
            'value' => old('estimated'),
            'error' => $errors->first('estimated'),
        ])
        @endcomponent
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
var cleaveOptions = { numeral: true };

new Cleave('input[name=cost_by_kg]', cleaveOptions);
new Cleave('input[name=min_kg]', cleaveOptions);

var $provinces = $('select[name=province_id]');
var $cities = $('select[name=city_id]');
var $subdistricts = $('select[name=subdistrict_id]');

$provinces.on('change', function() {
    var provinceId = $(this).val();

    $('#spinner-city').show();
    $('#input-city').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.cities') }}?province_id=" + provinceId,
        success: function(response) {
            $('#spinner-city').hide();
            $('#input-city').show();

            var cities = response.data;

            $cities.empty();
            $cities.append('<option value="">Pilih Kota/Kabupaten</option>');

            for (const city of cities) {
                $cities.append('<option value="' + city.id + '">' + city.name + '</option>');
            }

            $subdistricts.empty();
            $subdistricts.append('<option value="">Pilih Kecamatan</option>');
        },
        error: function() {
            $('#spinner-city').hide();
            $('#input-city').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar kota/kabupaten',
                position: 'topRight'
            });
        }
    });
});

$cities.on('change', function() {
    var cityId = $(this).val();

    $('#spinner-subdistrict').show();
    $('#input-subdistrict').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.subdistricts') }}?city_id=" + cityId,
        success: function(response) {
            $('#spinner-subdistrict').hide();
            $('#input-subdistrict').show();

            var subdistricts = response.data;

            $subdistricts.empty();
            $subdistricts.append('<option value="">Pilih Kecamatan</option>');

            for (const subdistrict of subdistricts) {
                $subdistricts.append('<option value="' + subdistrict.id + '">' + subdistrict.name + '</option>');
            }
        },
        error: function() {
            $('#spinner-subdistrict').hide();
            $('#input-subdistrict').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar kecamatan',
                position: 'topRight'
            });
        }
    });
});
</script>
@endsection
