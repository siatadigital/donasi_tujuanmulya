@extends('admin::layouts.default')
<style>
	#inventory-invoice a {
		text-decoration: none ! important;
	}

	.invoice {
		position: relative;
		background-color: #FFF;
		min-height: 680px;
	}

	.invoice header {
		padding: 10px 0;
		margin-bottom: 20px;
		border-bottom: 1px solid #998306
	}

	.company-details {
		text-align: right;
		/* max-width:400px; */
	}

	.company-details .name {
		margin-top: 0;
		margin-bottom: 0;
		color: #998306
	}

	.invoice .contacts {
		margin-bottom: 20px
	}

	.invoice .invoice-to {
		text-align: left
	}

	.invoice .invoice-to .to {
		margin-top: 0;
		margin-bottom: 0
	}

	.invoice .invoice-details {
		text-align: right
	}

	.invoice .invoice-details .invoice-id {
		margin-top: 0;
		color: #998306
	}

	.invoice main {
		padding-bottom: 50px
	}

	.invoice main .thanks {
		margin-top: -100px;
		font-size: 2em;
		margin-bottom: 50px
	}

	.invoice main .notices {
		padding-left: 6px;
		border-left: 6px solid #998306
	}

	.invoice main .notices .notice {
		font-size: 1em
	}

	.invoice table {
		width: 100%;
		border-collapse: collapse;
		border-spacing: 0;
		margin-bottom: 20px
	}

	.invoice table.invtable td,
	.invoice table th {
		padding: 15px;
		background: #eee;
	}

	.invoice table th {
		white-space: nowrap;
		font-weight: 400;
		font-size: 16px;
		border: 1px solid #fff;
	}

	.invoice table td {
		border: 1px solid #fff;
	}

	.invoice table td h3 {
		margin: 0;
		font-weight: 400;
		color: #998306;
		font-size: 1em
	}

	.invoice table .tax,
	.invoice table .total,
	.invoice table .unit {
		text-align: right;
		font-size: 1em
	}

	.invoice table .no {
		color: #fff;
		font-size: 1.6em;
		background: #9c8816
	}

	.invoice table .unit {
		background: #ddd
	}

	.invoice table .total {
		background: #9c8816;
		color: #fff;
		font-size: 1.6em;
	}

	.invoice table tfoot td {
		background: 0 0;
		border-bottom: none;
		white-space: nowrap;
		text-align: right;
		padding: 10px 20px;
		font-size: 1.4em;
		border-top: 1px solid #aaa
	}

	.invoice footer {
		width: 100%;
		text-align: center;
		color: #777;
		border-top: 1px solid #aaa;
		padding: 8px 0
	}
</style>
@section('content')

<div class="nav-tabs-custom">

	<div class="tab-content">
		<br />
		<form action="{{ route("admin.transaksi.getSuccessTransaksiExport") }}" method="post">
			{{ csrf_field() }}
			<div class="row">
				<div class="input-daterange">
					<div class="col-md-3">
						<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}" readonly />
					</div>
					<div class="col-md-3">
						<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}" readonly />
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3">
					<input type="text" name="cari" id="cari" class="form-control" placeholder="Search" value="{{ request('cari') }}" />
				</div>
				<div class="col-md-3">
					<select name="type_cari" class="form-control" id="type_cari">
						<option value="Pilih Tipe Cari">Pilih Tipe Cari</option>
						<option value="Invoice" @if(request('type_cari')=='Invoice' ) selected @endif>Kode Invoice</option>
						<option value="Judul Infak Terikat" @if(request('type_cari')=='Judul Infak Terikat' ) selected @endif>Judul Infak Terikat</option>
						<option value="Nama Pemberi Infak" @if(request('type_cari')=='Nama Pemberi Infak' ) selected @endif>Nama Pemberi Infak</option>
						<option value="No. WhatsApp" @if(request('type_cari')=='No. WhatsApp' ) selected @endif>No. WhatsApp</option>
						<option value="Bank Tujuan" @if(request('type_cari')=='Bank Tujuan' ) selected @endif>Bank Tujuan</option>
						<option value="Nominal/Kode Unik" @if(request('type_cari')=='Nominal/Kode Unik' ) selected @endif>Nominal/Kode Unik</option>
						<option value="Email" @if(request('type_cari')=='Email' ) selected @endif>Email</option>
						<option value="Kota" @if(request('type_cari')=='Kota' ) selected @endif>Kota</option>
					</select>
				</div>
				{{-- <div class="col-md-3">
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
				</div> --}}
			</div>
			<br>
			<div>
				<a name="filter" id="filter" class="btn btn-primary">Filter</a>
				{{-- <input type="submit" id="export" value="Export Excel" class="btn btn-success"> --}}
				<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
			</div>
		</form>
		<br><br>
		<h2 class="pull-left">Total Transaksi {{ priceFormat($total) }}</h2>
		<br><br><br>
		<hr>
		<p class="text-right">{{ $count }} transaksi ditemukan</p>
		<table id="datatable" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style="width: 15%;">Kode Invoice</th>
					<th style="width: 15%;">Nama Pemberi Infak</th>
					<th style="width: 15%;">Nominal</th>
					<th style="width: 22%;">Details</th>
					<th style="width: 14%;">Status</th>
					<th style="width: 10%;">Tanggal</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($transactions as $item)
				<tr>
					<td>#MH{{ $item['id'] }}</td>
					<td>{{ $item['fullname'] }}</td>
					<td>{{ priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']) }}</td>
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
					</td>
					<td>{{ strtoupper($item['status']="ACCEPT"?"SUCCESS":$item['status']) }}</td>
					<td>{{ formatTime($item['created_at'], 'd F Y, H:i') }}</td>
					<td>
						<span class="btn btn-success btn-sm ViewData" data-token="{{Crypt::encrypt($item['id']."-".$item['endpoint'])}}"> <i class="fa fa-file-o"> </i> Lihat</span>
						<a class="btn btn-primary btn-sm" href="/invoice/{{Crypt::encrypt($item['id']."-".$item['endpoint'])}}" target="_blank"> <span class="fa fa-print"></span> Cetak</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="text-right" style="margin-right: 30px;">
			{!! $paginator->links() !!}
		</div>
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-lg ">
			<div class="modal-header text-right">
				<a class="btn btn-primary btn-sm PrintView" href="#" target="_blank"> <span class="fa fa-print"></span> Cetak</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			<div class="modal-body">
				<div id="inventory-invoice">

					<div class="invoice overflow-auto">
						<div style="border-bottom: #9c8816 2px solid; margin-bottom:15px">
							<table>
								<tr class="row">
									<td>
										<img src="https://tujuanmulia.id/public/images/logo_n1.png" alt="peduli" width="200px" class="pull-left mx-auto">
									</td>
									<td style="width: 50%">
										<div class="col company-details">
											<h2 class="name">yukdonas.org </h2>
											<div>Al Barokah Block. C No. 11 RT. 006 Rw. 009 Lebakwangi Sepatin Timur Kab. Tangerang Banten</div>
											<div>WhatsApp : +6285711122646</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div style="min-width: 600px">
							<main>
								<table>
									<tr>
										<td>
											<div class="col invoice-to">
												<div class="text-gray-light">INVOICE TO:</div>
												<h2 class="to"> </h2>
												<table style="border:0" cellspacing="0" cellpadding="0">
													<tr>
														<td style="width: 60px">Kota</td>
														<td style="width: 10px">:</td>
														<td>
															<div class="address"> </div>
														</td>
													</tr>
													<tr>
														<td>Email</td>
														<td>:</td>
														<td>
															<div class="email"> </div>
														</td>
													</tr>
													<tr>
														<td>Telepon</td>
														<td>:</td>
														<td>
															<div class="phone"></div>
														</td>
													</tr>
												</table>
											</div>
										</td>
										<td>
											<div class="col invoice-details">
												<h1 class="invoice-id">INVOICE #MH<span class="Invo"></span></h1>
												<div class="date">Tanggal Invoice: <span class="DateInv"></span></div>
											</div>
										</td>
									</tr>
								</table>
								<table class="invtable" border="0" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<th style="text-align:left">NAMA DONASI</th>
											<th style="text-align:right">NOMINAL</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left">
												<h3 class="Donasi"></h3>
												<div class="descript"></div>
											</td>
											<td class="total" style="background-color: #9c8816"> </td>
										</tr>
									</tbody>
								</table>
								<table>
									<tr>
										<td style="text-align: left">
											<div>Metode Pembayaran</div>
											<h3 class="payment"></h3>
										</td>
										<td style="text-align: right">
											<div>Status Pembayaran</div>
											<h3 class="status"></h3>
										</td>
									</tr>
								</table>
								<div class="notices">
									<div>NOTICE:</div>
									<div class="notice">Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.</div>
								</div>
							</main>
							<div style=" font-size: 1em;text-align:center;margin-bottom:10px">Terimakasih atas kepercayaannya. Untuk informasi program infak/zakat lainnya, silahkan kunjungi <span><a href="{{ url() }}">tujuanmulia.id</a></span>
							</div>
							<footer>
								Invoice was generated on a computer and is valid without the signature and seal.
							</footer>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
<!-- Modal -->

@section('scripts')
<script>
	$(document).ready(function() {
		$(window).keydown(function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				$('#filter').click();
			}
		});

		$('.input-daterange').datepicker({
			todayBtn: 'linked',
			format: 'yyyy-mm-dd',
			autoclose: true
		});

		$('#filter').click(function() {
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

		$('#export').click(function() {
			var cari = $('#cari').val();
			var type_cari = $('#type_cari').val();
			var isSearchTypeNeeded = cari && type_cari == 'Pilih Tipe Cari';

			if (isSearchTypeNeeded) {
				alert('Pilih Tipe Cari Harus Diisi');
				return false;
			}
		});

		$('#refresh').click(function() {
			window.location.href = '?page=1';
		});
		$('.ViewData').click(function() {
			var id = $(this).data('token');

			$(".to").html("")
			$(".address").html("")
			$(".email").html("")
			$(".phone").html("")
			$(".Invo").html("")
			$(".DateInv").html("")
			$(".Donasi").html("")
			$(".descript").html("")
			$(".total").html("")
			$(".payment").html("")
			$(".status").html("")
			$(".PrintView").attr('href', `#`);
			$.ajax({
				type: "GET",
				url: `/invoice_view/${id}`,
				dataType: "json",
				error: function() {
					alert('failure');
				},
				success: function(data) {
					// console.log(data.transactions);
					$(".to").html(data.transactions.fullname)
					$(".address").html(data.transactions.city)
					$(".email").html(data.transactions.email)
					$(".phone").html(data.transactions.phone)
					$(".Invo").html(data.transactions.id)
					$(".DateInv").html(formarDate(data.transactions.created_at))
					$(".Donasi").html(data.transactions.akad)
					$(".descript").html(data.transactions.project_title)
					$(".total").html(NumFormat(data.transactions.amount))
					$(".payment").html(data.transactions.data_payment_method)
					$(".status").html(data.transactions.status == "accept" ? "success" : data.transactions.status)
					$(".PrintView").attr('href', `/invoice/${id}`);
					$("#exampleModal").modal()
				},
			});
		});
	});

	function confirm_check(id, endpoint) {
		$.ajax({
			type: "GET",
			url: `/backend/${endpoint}/confirm_check/${id}`,
			success: function() {
				location.reload();
			},
			error: function() {
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
			error: function() {
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
			data: {
				note: note,
				id: id
			},
			success: function(data) {
				alert('Berhasil Disimpan!');
				location.reload();
			},
			error: function() {
				alert('failure');
				location.reload();
			}
		});
	}

	function formarDate(e) {
		var date = new Date(e);
		var month = ["January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"
		][date.getMonth()];
		var day = date.getDate();
		var FullDate = `${day} ${month} ${date.getFullYear()}, ${date.getHours()}:${date.getMinutes()}`;
		return FullDate;
	}

	function NumFormat(num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
	}
</script>
@stop