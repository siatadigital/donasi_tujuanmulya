@component('mail::message')
# Ubah Penjualan

<p>Anda menerima email ini karena ada permintaan dari <strong>{{ $data->name }}</strong> untuk ubah data penjualan.</p>

<p style="text-align:center;margin-top:32px;">Masukan kode berikut pada input kode OTP:</p>
<p style="font-size:24px;font-weight:bold;text-align:center;">{{ $data->code }}</p>

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
