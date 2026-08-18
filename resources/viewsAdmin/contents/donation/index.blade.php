@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<br />
			@if(Route::current()->getName() == "admin.donation.getSuccessDonation")
				<form action="{{ route("admin.donation.getSuccessDonationExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.donation.getPendingDonation") 
				<form action="{{ route("admin.donation.getPendingDonationExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.donation.getExpiredDonation") 
				<form action="{{ route("admin.donation.getExpiredDonationExport") }}" method="post">
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
				<div class="col-md-2">
					<select name="type_cari" class="form-control" id="type_cari" >
						<option value="Pilih Tipe Cari">Pilih Tipe Cari</option>
						<option value="Nama Pemberi Infak" @if(request('type_cari') == 'Nama Pemberi Infak') selected @endif>Nama Pemberi Infak</option>
						<option value="No. WhatsApp" @if(request('type_cari') == 'No. WhatsApp') selected @endif>No. WhatsApp</option>
						<option value="Bank Tujuan" @if(request('type_cari') == 'Bank Tujuan') selected @endif>Bank Tujuan</option>
						<option value="Nominal/Kode Unik" @if(request('type_cari') == 'Nominal/Kode Unik') selected @endif>Nominal/Kode Unik</option>
						<option value="Email" @if(request('type_cari') == 'Email') selected @endif>Email</option>
						<option value="Kota" @if(request('type_cari') == 'Kota') selected @endif>Kota</option>
					</select>
				</div>
				<div class="col-md-4">
					<a name="filter" id="filter" class="btn btn-primary">Filter</a>
					@if(Route::current()->getName() == "admin.donation.getSuccessDonation")
						@if (isPermitted('admin.donation.getSuccessDonationExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.donation.getPendingDonation") 
						@if (isPermitted('admin.donation.getPendingDonationExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.donation.getExpiredDonation") 
						@if (isPermitted('admin.donation.getExpiredDonationExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@endif
					<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
				</div>
			</div>
		</form>
		<br><br>
		<h2 class="pull-left">Total Infak Umum {{ priceFormat($total) }}</h2>
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
          @foreach($donations as $item)
					<tr>
						<td>{{ $item['fullname'] }}</td>
						<td>
							<div>
								<label>Nominal : </label> {{ priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']) }}
							</div>
              <div>
								<label>Bank : </label> {{ ($item['data_payment_method'] ? $item['data_payment_method']['name'] : '') }}
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
							@if (isPermitted('admin.donation.submitNote'))
								<form onsubmit="return false">
									<textarea id="note{{ $item['id'] }}">{{ $item['check_note'] }}</textarea> 
									<button type="submit" class="btn btn-success btn-sm" onclick="submit_note('{{ $item->id }}')">
										<i class="glyphicon glyphicon-check"></i>
									</button>
								</form>
							@endif
						</td>
						<td>
							@if($item['is_checked'] == false)
								@if (isPermitted('admin.donation.confirmCheck'))
									Belum Dicek <br>
									<button class="btn btn-success btn-sm" onclick="confirm_check('{{ $item->id }}')" >Confirm Check</button>
								@endif
							@else
								@if (isPermitted('admin.donation.cancelCheck'))
									Sudah Dicek <br>
									<button class="btn btn-danger btn-sm" onclick="cancel_check('{{ $item->id }}')" >Cancel Check</button>
								@endif
							@endif
						</td>
						<td>
							@if (isPermitted('admin.donation.putRejectDonation'))
								{!! Form::link("Reject", 'PUT', route('admin.donation.putRejectDonation', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}
							@endif
							@if (isPermitted('admin.donation.putSuccessDonation'))
								{!! Form::link("Accept", 'PUT', route('admin.donation.putSuccessDonation', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}
							@endif
						</td>
					</tr>
          @endforeach
				</tbody>
			</table>
      <div class="text-right" style="margin-right: 30px;">
        {!! $donations->render() !!}
      </div>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
@stop

@section('scripts')
@include('admin::partials.filter-js')

<script>
	function confirm_check(id) {
		$.ajax({
			type: "GET",
			url: '/backend/donation/confirm_check/' + id,
			success: function() {
				location.reload();
			},
			error:function(){
				alert('failure');
			}
		});
	}

	function cancel_check(id) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "GET",
			url: '/backend/donation/cancel_check/' + id,
			success: function() {
				location.reload();
			},
			error:function(){
				alert('failure');
			}
		});
	}

	function submit_note(id) {
		var note = $("#note" + id).val();
			
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
			url: '/backend/donation/submit_note',
			data: {note: note, id:id},
			success: function(data) {
				alert('Berhasil Disimpan!');
				location.reload();
			},
			error:function(){
				alert('failure');
				location.reload();
			}
		});
	}
</script>

@stop