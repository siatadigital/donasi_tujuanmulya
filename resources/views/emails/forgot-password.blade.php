@component('mail::message')
# Lupa Password

Anda menerima email ini karena ada permintaan untuk seting ulang kata sandi akun anda.

@component('mail::button', ['url' => config('rtl.password_reset_url') . '?email=' . $data->email . '&token='. $data->token])
Reset Password
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
