@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<br />
			@if(Route::current()->getName() == "admin.transaksi.getSuccessTransaksi")
				<form action="{{ route("admin.transaksi.getSuccessTransaksiExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.transaksi.getPendingTransaksi")
				<form action="{{ route("admin.transaksi.getPendingTransaksiExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.transaksi.getExpiredTransaksi")
				<form action="{{ route("admin.transaksi.getExpiredTransaksiExport") }}" method="post">
			@endif
			{{ csrf_field() }}
			<div class="row">
				<div class="input-daterange">
					<div class="col-md-2">
						<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}" readonly />
					</div>
					<div class="col-md-2">
						<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}" readonly />
					</div>
				</div>
				<div class="col-md-2">
					<input type="text" name="cari" id="cari" class="form-control" placeholder="Search" value="{{ request('cari') }}" />
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3">
					<select name="type_cari" class="form-control" id="type_cari" >
						<option value="Pilih Tipe Cari">Pilih Tipe Cari</option>
						<option value="Judul Infak Terikat" @if(request('type_cari') == 'Judul Infak Terikat') selected @endif>Judul Infak Terikat</option>
						<option value="Nama Pemberi Infak" @if(request('type_cari') == 'Nama Pemberi Infak') selected @endif>Nama Pemberi Infak</option>
						<option value="No. WhatsApp" @if(request('type_cari') == 'No. WhatsApp') selected @endif>No. WhatsApp</option>
						<option value="Bank Tujuan" @if(request('type_cari') == 'Bank Tujuan') selected @endif>Bank Tujuan</option>
						<option value="Nominal/Kode Unik" @if(request('type_cari') == 'Nominal/Kode Unik') selected @endif>Nominal/Kode Unik</option>
						<option value="Email" @if(request('type_cari') == 'Email') selected @endif>Email</option>
						<option value="Kota" @if(request('type_cari') == 'Kota') selected @endif>Kota</option>
					</select>
				</div>
				<div class="col-md-3">
					<select name="type_akad" class="form-control" id="type_akad">
						<option value="Pilih Tipe Akad">Pilih Tipe Akad</option>
						<option value="Infak Terikat" @if(request('type_akad') == 'Infak Terikat') selected @endif>
							Infak Terikat
						</option>
						<option value="Infak Umum" @if(request('type_akad') == 'Infak Umum') selected @endif>
							Infak Umum
						</option>
						<option value="Zakat" @if(request('type_akad') == 'Zakat') selected @endif>
							Zakat
						</option>
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-6">
					<select name="category_ids" class="form-control select2" multiple="multiple" data-placeholder="Pilih kategori campaign" id="categories">
						@foreach($categories as $category)
							<option value="{{ $category['id'] }}" @if(!empty(request('category_ids')) && in_array($category['id'], request('category_ids'))) selected @endif>
								{{ $category['category_name'] }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			<br>
			<div>
				<a name="filter" id="filter" class="btn btn-primary">Filter</a>
				@if(Route::current()->getName() == "admin.transaksi.getSuccessTransaksi")
					@if (isPermitted('admin.transaksi.getSuccessTransaksiExport'))
					<input type="submit" id="export" value="Export Excel" class="btn btn-success">
					@endif
				@elseif(Route::current()->getName() == "admin.transaksi.getPendingTransaksi")
					@if (isPermitted('admin.transaksi.getPendingTransaksiExport'))
					<input type="submit" id="export" value="Export Excel" class="btn btn-success">
					@endif
				@elseif(Route::current()->getName() == "admin.transaksi.getExpiredTransaksi")
					@if (isPermitted('admin.transaksi.getExpiredTransaksiExport'))
					<input type="submit" id="export" value="Export Excel" class="btn btn-success">
					@endif
				@endif
				<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
			</div>
		</form>
		<br><br>
		<h2 class="pull-left">Total Transaksi {{ priceFormat($total) }}</h2>
		<br><br><br><hr>
			<p class="text-right">{{ $count }} transaksi ditemukan</p>
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Pemberi Infak</th>
						<th style="width: 30%;">Details</th>
						<th>Status</th>
						<th>Kode Unik</th>
						<th style="width: 10%;">Tanggal</th>
						<th>Catatan</th>
						<th>Status Cek</th>
						<th></th>
					</tr>
				</thead>
        <tbody>
          @foreach($transactions as $item)
					<tr>
						<td>{{ $item['fullname'] }}</td>
						<td>
							<div>
								<label>Tipe Transaksi: </label> {{ $item['akad'] }}
							</div>
							@if ($item['reward_id'])
							<div>
								<label>Order : </label><br>
								@foreach(json_decode($item['reward_id'], true) as $row)
								{{ $row['desc'].'('.$row['price'].' x '.$row['qty'].')' }}<br>
								@endforeach
							</div>
							@endif
							@if ($item['project_title'])
							<div>
								<label>Nama Project/Campaign : </label> {{ $item['project_title'] }}
							</div>
							@endif
							<div>
								<label>Nominal : </label> {{ priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']) }}
							</div>
              <div>
								<label>Bank : </label> {{($item['data_payment_method'] ? $item['data_payment_method'] : '') }}
							</div>
							<div>
								Email : {{ $item['email'] }} <br> No. WhatsApp : {{ $item['phone'] }} <br> <label>Dukungan/Doa : </label> {{ $item['notes'] }}
							</div>
              <div>
								<label>Kota : </label> {{ $item['city'] }}
							</div>
						</td>
						<td>{{ strtoupper($item['status']) }}</td>
						<td>{{ $item['unique_code'] ?: '-' }}</td>
						<td>{{ formatTime($item['created_at'], 'd F Y, H:i') }}</td>
						<td>
							@if (isPermitted("admin.{$item->endpoint}.submitNote"))
								<form onsubmit="return false">
									<textarea id="note{{ $item['id'] }}">{{ $item['check_note'] }}</textarea>
									<button type="submit" class="btn btn-success btn-sm" onclick="submit_note('{{ $item->id }}', '{{ $item->endpoint }}')">
										<i class="glyphicon glyphicon-check"></i>
									</button>
								</form>
							@endif
						</td>
						<td>
							@if($item['is_checked'] == false)
								@if (isPermitted("admin.{$item->endpoint}.confirmCheck"))
									Belum Dicek <br>
									<button class="btn btn-success btn-sm" onclick="confirm_check('{{ $item->id }}', '{{ $item->endpoint }}')" >Confirm Check</button>
								@endif
							@else
								@if (isPermitted("admin.{$item->endpoint}.cancelCheck"))
									Sudah Dicek <br>
									<button class="btn btn-danger btn-sm" onclick="cancel_check('{{ $item->id }}', '{{ $item->endpoint }}')" >Cancel Check</button>
								@endif
							@endif
						</td>
						<td>
							<?php
								$rejectSuffix = '';
								$acceptSuffix = '';

								switch ($item->endpoint) {
									case 'project':
										$rejectSuffix = 'putRejectSupporter';
										$acceptSuffix = 'putAcceptSupporter';
										break;

									case 'donation':
										$rejectSuffix = 'putRejectDonation';
										$acceptSuffix = 'putSuccessDonation';
										break;

									case 'zakat':
										$rejectSuffix = 'putRejectZakat';
										$acceptSuffix = 'putSuccessZakat';
										break;

									default:
										break;
								}
							?>

							@if (isPermitted("admin.{$item->endpoint}.{$rejectSuffix}"))
								{!! Form::link("Reject", 'PUT', route("admin.{$item->endpoint}.{$rejectSuffix}", ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}
							@endif
							@if (isPermitted("admin.{$item->endpoint}.{$acceptSuffix}"))
								{!! Form::link("Accept", 'PUT', route("admin.{$item->endpoint}.{$acceptSuffix}", ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}
							@endif
						{{-- <br>{{ $item->endpoint }}#{{ $acceptSuffix }} --}}
						</td>
					</tr>
          @endforeach
				</tbody>
			</table>
      <div class="text-right" style="margin-right: 30px;">
				{!!  $paginator->links() !!}
      </div>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
@stop

@section('scripts')
<script>
	$(document).ready(function(){
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				$('#filter').click();
			}
		});

		$('.input-daterange').datepicker({
			todayBtn:'linked',
			format:'yyyy-mm-dd',
			autoclose:true
		});

		$('#filter').click(function(){
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			var cari = $('#cari').val().trim();
			var type_cari = $('#type_cari').val();
			var type_akad = $('#type_akad').val();
			var categories = $('#categories').val();
			var isSearchTypeNeeded = cari && type_cari == 'Pilih Tipe Cari';

			if (isSearchTypeNeeded) {
				alert('Pilih Tipe Cari Harus Diisi');
				return;
			}

			var query = $.param({
				page: 1,
				from_date: from_date,
				to_date: to_date,
				cari: cari,
				type_cari: type_cari,
				category_ids: categories,
				type_akad: type_akad,
			});

			window.location.href = `?${query}`;
		});

		$('#export').click(function(){
			var cari = $('#cari').val();
			var type_cari = $('#type_cari').val();
			var isSearchTypeNeeded = cari && type_cari == 'Pilih Tipe Cari';

			if (isSearchTypeNeeded) {
				alert('Pilih Tipe Cari Harus Diisi');
				return false;
			}
		});

		$('#refresh').click(function(){
			window.location.href = '?page=1';
		});
	});

	function confirm_check(id, endpoint) {
		$.ajax({
			type: "GET",
			url: `/backend/${endpoint}/confirm_check/${id}`,
			success: function() {
				location.reload();
			},
			error:function(){
				alert('failure');
			}
		});
	}

	function cancel_check(id, endpoint) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "GET",
			url: `/backend/${endpoint}/cancel_check/${id}`,
			success: function() {
				location.reload();
			},
			error:function(){
				alert('failure');
			}
		});
	}

	function submit_note(id, endpoint) {
		var note = $(`#note${id}`).val();

		if (!note) {
			alert('Catatan harus diisi')
			return false;
		}

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "POST",
			url: `/backend/${endpoint}/submit_note`,
			data: {note: note, id:id},
			success: function(data) {
				alert('Berhasil Disimpan!');
				location.reload();
			},
			error: function () {
				alert('failure');
				location.reload();
			}
		});
	}
</script>
@stop
