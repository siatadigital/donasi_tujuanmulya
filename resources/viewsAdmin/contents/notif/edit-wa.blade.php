@extends('admin::layouts.default')
@section('head')
<script src="{{ asset('js/blog-create.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

<div class="nav-tabs-custom">

	<div class="tab-content">
		<form method="post" action="{{ url('/backend/notif/notif_wa/update/'.$notif->id) }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label mb-10">Type</label>
					<input type="text" name='type' id="type" value="{{ $notif->type }}" class="form-control" readonly required>
				</div>
			</div>
			<br><br><br><br>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Template</div>
					<div class="panel-body">
						@if ($notif->type == 'confirm_payment' or $notif->type == 'qurban_confirm_payment' or $notif->type == 'zakat_fitrah_confirm_payment')
						Terimakasih, Sahabat [fullname]
						<br><br>
						Semoga Allah ta'ala memudahkan niat baik Anda untuk bersedekah dan berzakat di https://tujuanmulia.id
						<br><br>
						Silahkan melanjutkan transaksi donasi *#ID [id]* dengan transfer :
						<br><br>
						Nominal : *[amount]* <br>
						Bank : *[bank_name]* <br>
						No. Rekening : *[bank_number]* <br>
						Atas nama : *[bank_account]*
						<br><br>
						PENTING!
						<br><br>
						1. Lakukan pembayaran TEPAT sebesar *[amount]* (sertakan kode unik *[unique_code]* supaya infak/zakat Anda mudah kami verifikasi.
						<br><br>
						2. Kami menunggu transfer infak/zakat Anda sampai dengan [expired_at]
						<br><br>
						butuh bantuan? silakan chatting WA dengan admin kami https://api.whatsapp.com/send?phone=6285711122646";
						<br><br>
						Salam, <br>
						https://tujuanmulia.id | *Yukdonasi*
						@elseif ($notif->type == 'confirm_success' or $notif->type == 'qurban_confirm_success' or $notif->type == 'zakat_fitrah_confirm_success')
						*KONFIRMASI INFAK/ZAKAT BERHASIL*
						<br><br>
						Terimakasih, Sahabat [fullname]<br>
						<br>
						Alhamdulillah, Infak/Zakat *#ID [id]* melalui https://tujuanmulia.id sebesar [amount] sudah kami terima<br>
						<br>
						آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا<br>
						<br>
						Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.<br>
						<br>
						Terimakasih atas kepercayaannya. Untuk informasi program infak/zakat lainnya, silahkan kunjungi https://tujuanmulia.id atau WA official : https://api.whatsapp.com/send?phone=6285711122646<br>
						<br>
						Salam,<br>
						https://tujuanmulia.id | *Yukdonasi*
						@elseif ($notif->type == 'confirm_expired' or $notif->type == 'qurban_confirm_expired' or $notif->type == 'zakat_fitrah_confirm_expired')
						*KONFIRMASI INFAK/ZAKAT TELAH LEWAT BATAS WAKTU*
						<br><br>
						Terimakasih, Sahabat [fullname]<br>
						Mohon Maaf Infak/Zakat *#ID [id]* sejumlah [amount], melalui *Transfer [bank_name]*, pada tanggal *[date_transfer]*, telah melewati batas waktu.<br>
						Silakan mengulang lagi transaksi infak/zakat Anda di https://tujuanmulia.id<br>
						<br>
						Apabila ada pertanyaan, silahkan WA admin official di https://api.whatsapp.com/send?phone=6285711122646<br>
						<br>
						Salam,<br>
						https://tujuanmulia.id | *Yukdonasi*
						@elseif ($notif->type == 'crm_offer')
						*PENAWARAN DARI ZAKAT KITA*
						<br><br>
						Assalamu'alaikum, Sahabat [fullname]<br>
						[content]
						<br><br>
						Apabila ada pertanyaan, silahkan WA admin official di https://api.whatsapp.com/send?phone=6285711122646
						<br><br>
						Salam,<br>
						https://tujuanmulia.id | *Yukdonasi*
						@endif
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label mb-10">Content</label>
					<textarea name='value' id="value" value="" class="form-control" rows="20" required>{{ $notif->value }}</textarea>
				</div>
			</div>
			<p class="text-center">
				{!! Form::submit('Save',['class'=>'btn btn-primary btn-lg']) !!}
			</p>
		</form>
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->

@stop