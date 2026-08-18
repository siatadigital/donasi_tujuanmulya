@extends('layouts.default')
@section('title','Validasi Akun')
@section('head')
<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.structure.min.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.theme.min.css') }}">
<style>
  #btn-validate-ktp2 {
    width: 100%;
    height: 300px;
    font-size: 20px;
    border: none;
    background: #E2E2E2;
    outline: 0;
    -webkit-transition: .4s;
    transition: .4s;
  }
</style>
<script src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>

<script>
  var $root = $("meta[name='root-url']").attr('content');

  $(function() {
    $("#preview-ktp").hide();
    $("#btn-validate-ktp").on('click',function() {
      $("#ktp-file").trigger('click');
    })

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $("#btn-validate-ktp").css({ "height":"50px", "width": "250", "margin":"auto" }).html("Ubah foto");
          $('#preview-ktp').attr('src', e.target.result).fadeIn(200);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#ktp-file").change(function(){
        readURL(this);
        var image = $(this).prop("files")[0];
        var data = new FormData();
        data.append("file",image);
        var url = upload_image(data, function(url) {
          $("#fotoktp").val(url);
          $("#buttonfoto").removeAttr( "disabled" );
        });
    });

    function upload_image(file, thecallback)
    {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      $.ajax({
        data: file,
        url: $root + "/api/v1/media/upload",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url)
        {
          thecallback(url);
        }
      });
    }
  })
</script>

<script>
  var $root = $("meta[name='root-url']").attr('content');

  $(function() {
    $("#preview-ktp2").hide();
    $("#btn-validate-ktp2").on('click',function() {
      $("#ktp-file2").trigger('click');
    })

    function readURL2(input) {
      if (input.files && input.files[0]) {
        var reader2 = new FileReader();

        reader2.onload = function (e) {
          $("#btn-validate-ktp2").css({ "height":"50px", "width": "250", "margin":"auto" }).html("Ubah foto");
          $('#preview-ktp2').attr('src', e.target.result).fadeIn(200);
        }
        reader2.readAsDataURL(input.files[0]);
      }
    }

    $("#ktp-file2").change(function(){
        readURL2(this);
        var image = $(this).prop("files")[0];
        var data = new FormData();
        data.append("file",image);
        var url = upload_image2(data, function(url) {
          $("#foto_with_ktp").val(url);
        });
    });

    function upload_image2(file, thecallback)
    {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      $.ajax({
        data: file,
        url: $root + "/api/v1/media/upload",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url)
        {
          thecallback(url);
        }
      });
    }
  })
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $(function(){
      $('#select_province').change(function(){
        $.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') } });

        var $root = $("meta[name='root-url']").attr('content');

        id_prov = $(this).val();

        $('#province').val($(this).children("option:selected").text());

        $.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') } });

        var $root = $("meta[name='root-url']").attr('content');

        $.ajax({
            url: $root + "/api/v1/getkota",
            type: 'POST',
            data: { 'provinsi': id_prov },
            success: function (data) {
                $('#select_city').html('');
                $('#select_city').append(data);
                $('#select_city').prop('disabled', false);
                $('#city').val($('#select_city').children("option:selected").text())
            }
        });
      });

      $('#select_city').change(function() {
        var cityName = $(this).children("option:selected").text();

        $('#city').val(cityName);
      });
    });
  });
</script>
@stop
@section('content')
  <div class="container-mobile" style="margin-bottom: 100px;">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <header class="page-header text-center">
          <h1>Validasi Akun</h1>
        </header>
        
        <section class="text-center">
          <button type="button" id="btn-validate-ktp">
            <i class="fa fa-file-image-o"></i><br>
            Upload Foto KTP
          </button>
          <img src="" id="preview-ktp" class="img-responsive" accept="image/*" style="margin:auto">
        </section>
        <br>
        <br>
        <ul style="padding-left:16px;">
          <li><p>Foto e-KTP asli (bukan salinan)</p></li>
          <li><p>Informasi pada e-KTP terlihat jelas dan tidak ada yang terpotong & buram</p></li>
          <li><p>Tidak ada pantulan cahaya dan bayangan pada foto e-KTP</p></li>
          <li><p>Foto kamu memenuhi area dari frame foto</p></li>
        </ul>
        <br>
        <br>
        <section class="text-center">
          <button type="button" id="btn-validate-ktp2">
            <i class="fa fa-file-image-o"></i><br>
            Upload Foto Bersama KTP
          </button>
          <img src="" id="preview-ktp2" class="img-responsive" accept="image/*" style="margin:auto">
        </section>
        <br>
        <br>
        <ul style="padding-left:16px;">
          <li><p>Pastikan foto wajah (selfie) dengan kartu identitas terlihat secara menyeluruh dan jelas.</p></li>
          <li><p>Pastikan kamu menggunakan kartu identitas yang sama dengan kartu identitas yang kamu unggah.</p></li>
          <li><p>Pastikan foto kartu identitas tidak menutupi wajah kamu.</p></li>
          <li><p>Foto wajah kamu (selfie) difoto secara langsung dari handphone kamu.</p></li>
        </ul>
        <br>
        <br>
        
        <section class="mt-20 text-center">
          {!! Form::open(['id'=>'kirim-foto']) !!}
          <br>
          <div class="form-group">
            <label>Full Name</label>
            {!! Form::text('name',$user->name,['class'=>'form-control','placeholder'=>'Fullname','required']) !!}
          </div>
          <br>
          <div class="form-group">
            <label>Birth Date</label>
            <input type="date" class="form-control birth_date" id="birth_date" required="required" name="birth_date" type="text" value="{{ $user->birth_date or '' }}">
          </div>
          <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" id="" required>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="form-group">
            <label>{{ trans('create_project.province') }}</label>
            <select name="select_province" id="select_province" class="form-control" required>
              <option value="0">{{ trans('create_project.province_select') }}</option>
              @foreach ($provinsi as $item)
              <option value="{{ $item->id }}">{{ $item->provinsi_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>{{ trans('create_project.city') }}</label>
            <select name="select_city" id="select_city" class="form-control" required disabled="true">
              <option value="0">{{ trans('create_project.city_select') }}</option>
            </select>
            <input type="hidden" value="" id="province" name="province">
            <input type="hidden" value="" id="city" name="city">
          </div>
          <div class="form-group">
            <label>Type Account</label>
            <select name="type_akun" class="form-control" id="" required>
              <option value="Personal">Personal</option>
              <option value="Lembaga">Lembaga</option>
            </select>
          </div>
            <div class="hidden">
              <input type="file" name="ktp" id='ktp-file' required>
            </div>
            {!! Form::text('fotoktp', '', ['class'=>'hidden','required','id'=>'fotoktp', 'value' => "" ] ) !!}
            
            <div class="hidden">
              <input type="file" name="ktp2" id='ktp-file2' required>
            </div>
            {!! Form::text('foto_with_ktp', '', ['class'=>'hidden','required','id'=>'foto_with_ktp', 'value' => "" ] ) !!}
            <button type="submit" id="buttonfoto" class="btn btn-primary btn-lg" disabled>Kirim</button>
          {!! Form::close() !!}
        </section>
      </div>
    </div>
  </div>
@stop