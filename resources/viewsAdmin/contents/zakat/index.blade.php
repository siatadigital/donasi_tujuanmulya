@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<br />
			@if(Route::current()->getName() == "admin.zakat.getSuccessZakat")
				<form action="{{ route("admin.zakat.getSuccessZakatExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.zakat.getPendingZakat") 
				<form action="{{ route("admin.zakat.getPendingZakatExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.zakat.getExpiredZakat") 
				<form action="{{ route("admin.zakat.getExpiredZakatExport") }}" method="post">
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
						<option value="Nama Pemberi Zakat" @if(request('type_cari') == 'Nama Pemberi Zakat') selected @endif>Nama Pemberi Zakat</option>
						<option value="Tipe Zakat" @if(request('type_cari') == 'Tipe Zakat') selected @endif>Tipe Zakat</option>
						<option value="No. WhatsApp" @if(request('type_cari') == 'No. WhatsApp') selected @endif>No. WhatsApp</option>
						<option value="Bank Tujuan" @if(request('type_cari') == 'Bank Tujuan') selected @endif>Bank Tujuan</option>
						<option value="Nominal/Kode Unik" @if(request('type_cari') == 'Nominal/Kode Unik') selected @endif>Nominal/Kode Unik</option>
						<option value="Email" @if(request('type_cari') == 'Email') selected @endif>Email</option>
						<option value="Kota" @if(request('type_cari') == 'Kota') selected @endif>Kota</option>
					</select>
				</div>
				<div class="col-md-4">
					<a name="filter" id="filter" class="btn btn-primary">Filter</a>
					@if(Route::current()->getName() == "admin.zakat.getSuccessZakat")
						@if (isPermitted('admin.zakat.getSuccessZakatExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.zakat.getPendingZakat") 
						@if (isPermitted('admin.zakat.getPendingZakatExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.zakat.getExpiredZakat") 
						@if (isPermitted('admin.zakat.getExpiredZakatExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@endif
					<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
				</div>
			</div>
		</form>
		<br><br>
		<h2 class="pull-left">Total Zakat {{ priceFormat($total) }}</h2>
		<br><br><br><hr>
			<p class="text-right">{{ $count }} transaksi ditemukan</p>
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Pemberi Zakat</th>
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
          @foreach($zakat as $item)
					<tr>
						<td>{{ $item['fullname'] }}</td>
						<td>
							<div>
								<label>Tipe : </label> {{ $item['type'] }}
							</div>
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
							@if (isPermitted('admin.zakat.submitNote'))
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
								@if (isPermitted('admin.zakat.confirmCheck'))
									Belum Dicek <br>
									<button class="btn btn-success btn-sm" onclick="confirm_check('{{ $item->id }}')" >Confirm Check</button>
								@endif
							@else
								@if (isPermitted('admin.zakat.cancelCheck'))
									Sudah Dicek <br>
									<button class="btn btn-danger btn-sm" onclick="cancel_check('{{ $item->id }}')" >Cancel Check</button>
								@endif
							@endif
						</td>
						<td>
							@if (isPermitted('admin.zakat.putRejectZakat'))
								{!! Form::link("Reject", 'PUT', route('admin.zakat.putRejectZakat', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}
							@endif
							@if (isPermitted('admin.zakat.putSuccessZakat'))
								{!! Form::link("Accept", 'PUT', route('admin.zakat.putSuccessZakat', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}
							@endif
						</td>
					</tr>
          @endforeach
				</tbody>
			</table>
      <div class="text-right" style="margin-right: 30px;">
        {!! $zakat->render() !!}
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
			url: '/backend/zakat/confirm_check/' + id,
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
			url: '/backend/zakat/cancel_check/' + id,
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
			url: '/backend/zakat/submit_note',
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