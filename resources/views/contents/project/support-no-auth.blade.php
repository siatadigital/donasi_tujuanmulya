@extends('layouts.default')

@section('head')
  <link rel="stylesheet" href="{{ asset('css/project-support.css') }}">

  @if ( ! empty(session('message')))
    <script type="text/javascript">
      window.onload = function()
      {
        swal({
          title: "{!! session('message.title') !!}",
          text: "{!! session('message.content') !!}",
          type: "{{ session('message.type') }}",
          html: true
        });
      };
    </script>
  @endif

@stop
@section('content')
  <div class="container" ng-app="support" ng-controller="defaultController" style="padding-bottom: 100px;">
    <header class="page-header text-center">
      <h1>Terima kasih atas dukungan untuk project <span class="highlight">{{ $title }}</span></h1>
    </header>

    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <header class="text-center">
          <h2>Details Dukungan</h2>
          <p>Selanjutnya anda tinggal melakukan Konfirmasi Pembayaran setelah melakukan Transfer berdasarkan Details dibawah ini.</p>
        </header>
        <hr>
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>Jumlah Transfer</td>
              <td><strong> {{ priceFormat( $support['unique_code'] + $support['money'] ) }} </strong></td>
            </tr>
            <tr>
              <td>Transfer Ke</td>
              <td>
                <br>
                <img src="{{ asset('images/'. $support['payment_method']) }}.png">
                <br>
                <strong>
                  AN : {{ $support['data_payment_method']['account_name'] }} <br>
                  No Rek : {{ $support['data_payment_method']['account_number_infak'] }}
                  <br><br>
                </strong>
              </td>
            </tr>
            <tr>
              <td>Status</td>
              <td>
                {{ getMessageSupport( $support['status'] ) }}
              </td>
            </tr>
          </tbody>
        </table>

        <div class="alert alert-info">
          <p class="lead">Pastikan anda Mentransfer sejumlah <strong>{{ priceFormat( $support['unique_code'] + $support['money'] ) }}</strong>, <br>
            {{ $support['unique_code'] }} adalah Kode Unik untuk memudahkan kami mengkonfirmasi pembayaran anda. Setelah Dukungan anda kami terima, kami akan segera memberitahu anda melalui {{ $support['phone'] }} <br> Terima Kasih.</p>
        </div>
        <hr>
        <div class="text-center">
          <h4><i class="fa fa-share"></i> Bagikan ke project ini ke teman dan kerabat anda.</h4>
          <div class="btn-group" role="group" aria-label="...">
            <a type="button" class="btn btn-primary" href="https://www.facebook.com/sharer/sharer.php?u={{ URL::route(
              'project.newGetShow', $slug) }}" target="_blank">
              <i class="fa fa-facebook"></i> facebook
            </a>
            <a type="button" class="btn btn-info" href="https://twitter.com/home?status=Saya telah mendukung Project Zakat Kita{{ URL::route(
              'project.newGetShow', $slug) }} #tujuanmulia.id" target="_blank">
              <i class="fa fa-twitter"></i> twitter
            </a>
            <a type="button" class="btn btn-danger" href="https://plus.google.com/share?url={{ URL::route(
              'project.newGetShow', $slug) }}" target="_blank">
              <i class="fa fa-google-plus"></i> google+
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
