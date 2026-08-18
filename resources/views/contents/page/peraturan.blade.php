@extends('layouts.default')
@section('title','Peraturan Umum')
@section('content')
    <div class="header-title">
    	<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
            <div class="container">
                <div class="header-title-content text-left">
                    <br>
                    <br>
                    <h1 class="content-title">Peraturan Umum</h1>
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
    <div class="container" style="padding: 80px 10px 100px 10px;font-size: 18px">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <p>
                  Zakat Kita didirikan berdasarkan keinginan untuk membantu para pengusaha Indonesia untuk lebih berkembang dan bermanfaat untuk kemajuan Indonesia. Kami mendukung berbagai kategori jenis bisnis seperti Kuliner, property, edukasi, produk kesehatan dan lain lain <br>
                  Ada 3 kriteria project:
                </p>

                <ol>
                  <li>
                    Bisnis harus memberikan efek positif untuk masyarakat. Zakat Kita adalah wadah untuk membuat sesuatu yang bisa membawa Indonesia menjadi lebih baik. Maka dari itu bisnis anda yang dibuat harus memberikan sesuatu yang positif bagi masyarakat
                  </li>
                  <li>
                    Bisnis anda tidak boleh menghasilkan barang terlarang dan melanggar hukum
                  </li>
                  <li>
                    Project harus orisinil dan tidak melanggar hak cipta Kami di Zakat Kita tidak ingin negara Indonesia menjadi sebuah negara yang menjiplak dan melanggar hak cipta produk orang.
                  </li>
                </ol>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@stop
