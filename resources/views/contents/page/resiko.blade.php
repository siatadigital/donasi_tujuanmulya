@extends('layouts.default')
@section('title','Resiko')
@section('content')
    <div class="header-title">
    	<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
            <div class="container">
                <div class="header-title-content text-left">
                    <br>
                    <br>
                    <h1 class="content-title">Resiko</h1>
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
                  Setiap kegiatan investasi yang dilakukan manusia tidak bisa terlepas dari resiko. Begitupun dengan kegiatan investasi  yang anda lakukan di Zakat Kita.<br>Resiko tersebut meliputi:
                </p>

                <ol>
                  <li>Usaha atau project yang ada di Zakat Kita bisa saja mengalami kegagalan, pailit hingga kebangkrutan.</li>

                  <li>
                    <em>high risk high return</em>, yang dimana setiap kegiatan investasi yang mengahasilkan pengembalian investasi yang tinggi juga akan disertai dengan berbagai resiko yang tinggi
                  </li>

                  <li>
                    Market atau pasar yang tidak dapat sepenuhnya diprediksi secara akurat mengenai arah trend bisa menjadi penyebab kegagalan sebuah bisnis. Maka dari itu dengan anda manjadi investor bersama dengan investor lain anda diharapkan untuk mendukung promosi setiap bisnis yang ada di Zakat Kita
                  </li>

                  <li>
                    Kebijakan pemerintah bisa menjadi salah satu faktor rugi atau gagalnya sebuah usaha yang dijalani maka dari itu diharapkan secara bersama kita selalu mengawal dan mendukung kebijakan pemerintah terhadap dunia bisnis di Indonesia
                  </li>
                </ol>
                <p>
                  Zakat Kita tidak bertanggung jawab atas setiap kerugian yang timbul dari kegiatan investasi yang anda (pengusaha dan Investor) lakukan. Karena Zakat Kita pada tujuan awalnya didirikan untuk menjadi Platform atau wadah untuk membantu para pengusaha dalam masalah permodalan dan kanal distribusi
                </p>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@stop
