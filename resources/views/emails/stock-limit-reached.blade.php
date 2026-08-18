@component('mail::message')
# Stok sudah mencapai limit

Anda menerima email ini karena stok kami untuk produk <strong>{{ $data['productName'] }}</strong> tinggal sedikit.

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
