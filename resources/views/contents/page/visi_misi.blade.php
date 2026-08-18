@extends('layouts.default')
@section('title','Visi & Misi')
@section('content')
    <div class="header-title">
    	<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
            <div class="container">
                <div class="header-title-content text-left">
                    <br>
                    <br>
                    <h1 class="content-title">Visi dan Misi</h1>
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
                  Zakat Kita menjadi wadah penggerak perekonomian Indonesia dengan memecahkan masalah permodalan yang sering dialami oleh para pengusaha di Indonesia serta menyediakan kanal distribusi bagi para pengusaha yang masih sulit untuk menyalurkan produk dan layanannya kepada masyarakat luas.
                </p>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@stop
