<div class="text-center" style="margin-bottom: 20px;">
  <i class="fa fa-check-circle" style="font-size: 72px;color: #b1c92a;"></i><br>
</div>
<div class="row">
  <div class="col-md-6 col-xs-6 text-center">
    <div><strong>Virtual Account</strong></div>
    <div>{{ $data['va_numbers'][0]['va_number'] }}</div>
  </div>
  <div class="col-md-6 col-xs-6 text-center">
    <div><strong>Bank</strong></div>
    <div>{{ strtoupper($data['va_numbers'][0]['bank']) }}</div>
  </div>
</div>
<br>
<br>
<div class="row">
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    <strong>Total Transfer</strong>
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    {{ priceFormat($donation['amount']) }}
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    <strong>Nama</strong>
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    @if ($donation['is_anonim'])
      Hamba Allah
    @else
      {{ $donation['fullname'] }}
    @endif
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    <strong>No. Whatsapp</strong>
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    {{ $donation['phone'] }}
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    <strong>Email</strong>
  </div>
  <div class="col-md-6 col-xs-6" style="margin-bottom: 5px;">
    {{ $donation['email'] }}
  </div>
</div>