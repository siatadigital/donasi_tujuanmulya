@extends('layouts.default')
@section('title','Cara Galang Dana')
@section('content')
    <div class="header-title">
    	<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
            <div class="container">
                <div class="header-title-content text-left">
                    <br>
                    <br>
                    <h1 class="content-title">Cara Galang Dana</h1>
                    <!-- <p class="content-subtitle">Mari membantu dengan sesama, dengan ikhlas dan tanpa pamrih</p> -->
                </div>
            </div>
        </div>
    </div>
    <style>
        ol{
            padding: 5px 15px;
        }
        li{
            margin-bottom: 5px;
        }
    </style>
    <div class="container">
        <div class="row" style="margin: 50px 0;">
            <div class="col-md-8">
                <img src="{{ asset('images/tutorial/1.jpg') }}" alt="" class="img-responsive">
            </div>

            <div class="col-md-4">
                <p>
                    Pastikan anda telah login atau mendaftar di PeduliIndonesia.com
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-8">
                <img src="{{ asset('images/tutorial/2.jpg') }}" alt="" class="img-responsive">
            </div>

            <div class="col-md-4">
                <p>
                    Masuk ke setting dan pastikan akun anda telah terverifikasi, atau jika tidak masuk ke tujuanmulia.id/username/validate dan kirim foto hasil scan KTP.
                </p>

                <p>
                    Jika anda mendapat pesan verifikasi seperti gambar disamping berarti anda telah siap untuk membuat project.
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-8">
                <img src="{{ asset('images/tutorial/3.jpg') }}" alt="" class="img-responsive">
            </div>

            <div class="col-md-4">
                <p>
                    Jika dua langkah diatas telah anda selesaikan maka anda siap membuat project baru dan mengisi semua form yang telah disediakan.
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-8">
                <img src="{{ asset('images/tutorial/4.jpg') }}" alt="" class="img-responsive">
            </div>

            <div class="col-md-4">
                <p>
                    Tambahkan reward jika anda menyediakan reward terhadap project yang sedang anda buat.
                </p>
            </div>
        </div>
    </div>
@stop
