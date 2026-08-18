@extends('admin::layouts.default')
@section('head')
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<form method="post" action="{{ url('/backend/popup/transaksi/update/'.$notif->id) }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Type</label>
						<input type="text" name='type' id="type" value="{{ $notif->type }}" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Template</label>
						@if($notif->type == 'transaksi_transfer_success')
						<textarea name='value' id="summernote2" disabled>
							<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">Transfer sesuai nominal dibawah ini:</h4>
							<div class="">
								<div class="row text-center">
								<div class="col-md-6 col-md-offset-3" style="font-size: 25px;font-weight: bold;">[nominal_kodeunik_html]</div>
								<div class="col-md-2" style="padding:10px;font-weight: bold;"><a href="#" id="nominal-salin">SALIN</a></div>
									</div><br>
										[input_copy_nominal_kodeunik]
									<div class="alert alert-warning" role="alert">
										<table>
											<tr>
												<td valign="top"><i class="fa fa-warning"></i> </td>
												<td>&nbsp;&nbsp;&nbsp;</td>
												<td><b>PENTING!</b> Mohon transfer tepat sampai 3 angka terakhir agar transaksi terverifikasi otomatis</td>
											</tr>
										</table>
									</div>
									<ul class="list-group">
										<li class="list-group-item">
											<span class="pull-right">[nominal]</span>
											Jumlah Transaksi
										</li>
										<li class="list-group-item">
											<span class="pull-right">[kodeunik]</span>
											Kode Unik (*)
										</li>
									</ul>
									<p>* 3 angka terakhir akan dimasukkan transaksi.</p>
									<br>
									<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">Pembayaran dilakukan ke rekening a/n</h4>
									
								<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;"><b> [bank_account_name]</b></h4>
								
								<div class="panel panel-default">
									<div class="panel-body">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3">
											[bank_name]
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6 text-center">
										<b>[bank_account_number]</b>
										[input_copy_bank_account_number]
										</div>
										<div class="col-xs-2 col-sm-2 col-md-2">
										<a href="#" id="nomor-rekening">
											<b>SALIN</b>
										</a>
										</div>
									</div>
									</div>
								</div>
									<div class="panel panel-default">
										<div class="panel-body">
											<p>Transfer transaksi sebelum <b>[date_expired] WIB</b> atau zakat Anda otomatis dibatalkan oleh sistem.</p>
										</div>
									</div>
									<ul class="list-group">
										<li class="list-group-item">
											<span class="pull-right">[type_transaction]</span>
											<strong>Jenis Transaksi</strong>
										</li>
										<li class="list-group-item">
											<span class="pull-right">
									[user_name]
									</span>
											<strong>Nama</strong>
										</li>
										<li class="list-group-item">
											<span class="pull-right">[user_phone]</span>
											<strong>No. Whatsapp</strong>
										</li>
										<li class="list-group-item">
											<span class="pull-right">[user_email]</span>
											<strong>Email</strong>
										</li>
									</ul>
								<br>
								<div style="display: flex;">
								<a type="button" style="background: #30b042; color: white; margin-right: 10px;align-items: center;display:flex;" class="btn btn-share" href="[share_url]" target="_blank">
									<i class="fa fa-whatsapp padding-right-10 share-icon" style="margin-right:5px"></i> Bagikan
								</a>
									<button type="button" class="btn btn-blue-large" data-dismiss="modal">{{ trans('homepage.kembali') }}</button>
								</div>
							</div>
						</textarea>
						@else
						<textarea name='value' id="summernote2" disabled>
							<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">Transfer sesuai nominal dibawah ini:</h4>
							<div class="">
								<div class="row text-center">
									<div class="col-md-6 col-md-offset-3" style="font-size: 25px;font-weight: bold;">[nominal_kodeunik_html]</div>
									<div class="col-md-2" style="padding:10px;font-weight: bold;"><a href="#" id="nominal-salin">SALIN</a></div>
								</div>
								<br>
								[input_copy_nominal_kodeunik]
								<div class="alert alert-warning" role="alert">
									<table>
										<tr>
											<td valign="top"><i class="fa fa-warning"></i> </td>
											<td>&nbsp;&nbsp;&nbsp;</td>
											<td><b>PENTING!</b> Mohon transfer melalui [text_info_payment] agar infak/zakat terverifikasi otomatis</td>
										</tr>
									</table>
								</div>
								<ul class="list-group">
									<li class="list-group-item">
										<span class="pull-right">[nominal]</span>
										Jumlah Infak/Zakat
									</li>
								</ul>
								<br>
								<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
									Pembayaran dilakukan [text_info_tujuan]
								</h4>
								<div class="panel panel-default">
									[action_payment]
								</div>
								<div class="panel panel-default">
									<div class="panel-body">
										<p>Transfer infak/zakat sebelum <b>[date_expired] WIB</b> atau infak/zakat Anda otomatis dibatalkan oleh sistem.</p>
									</div>
								</div>
								<ul class="list-group">
									<li class="list-group-item">
										<span class="pull-right">[type_transaction]</span>
										<strong>Jenis Transaksi</strong>
									</li>
									<li class="list-group-item">
										<span class="pull-right">
										[user_name]
										</span>
										<strong>Nama</strong>
									</li>
									<li class="list-group-item">
										<span class="pull-right">[user_phone]</span>
										<strong>No. Whatsapp</strong>
									</li>
									<li class="list-group-item">
										<span class="pull-right">[user_email]</span>
										<strong>Email</strong>
									</li>
								</ul>
								<br>
								<div style="display: flex;">
									<a type="button" style="background: #30b042; color: white; margin-right: 10px;align-items: center;display:flex;" class="btn btn-share" href="[share_url]" target="_blank">
									<i class="fa fa-whatsapp padding-right-10 share-icon" style="margin-right:5px"></i> Bagikan
									</a>
									<button type="button" class="btn btn-blue-large" data-dismiss="modal">{{ trans('homepage.kembali') }}</button>
								</div>
							</div>
						</textarea>
						@endif
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Content</label>
						<textarea name='value' id="summernote" required>{{ $notif->value }}</textarea>
					</div>
				</div>
				<p class="text-center">
					{!! Form::submit('Save',['class'=>'btn btn-primary btn-lg']) !!}
				</p>
      		</form>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
@section('scripts')
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			height:300
		});
		$('#summernote2').summernote({
			height:300
		});
	});
</script>
@stop