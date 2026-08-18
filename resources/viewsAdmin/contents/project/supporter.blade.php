@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<br />
			@if(Route::current()->getName() == "admin.project.getSuccessSupporter")
				<form action="{{ route("admin.project.getSuccessSupporterExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.project.getPendingSupporter")
				<form action="{{ route("admin.project.getPendingSupporterExport") }}" method="post">
			@elseif(Route::current()->getName() == "admin.project.getExpiredSupporter")
				<form action="{{ route("admin.project.getExpiredSupporterExport") }}" method="post">
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
						<option value="Judul Infak Terikat" @if(request('type_cari') == 'Judul Infak Terikat') selected @endif>Judul Infak Terikat</option>
						<option value="Nama Pemberi Infak" @if(request('type_cari') == 'Nama Pemberi Infak') selected @endif>Nama Pemberi Infak</option>
						<option value="No. WhatsApp" @if(request('type_cari') == 'No. WhatsApp') selected @endif>No. WhatsApp</option>
						<option value="Bank Tujuan" @if(request('type_cari') == 'Bank Tujuan') selected @endif>Bank Tujuan</option>
						<option value="Nominal/Kode Unik" @if(request('type_cari') == 'Nominal/Kode Unik') selected @endif>Nominal/Kode Unik</option>
						<option value="Email" @if(request('type_cari') == 'Email') selected @endif>Email</option>
						<option value="Kota" @if(request('type_cari') == 'Kota') selected @endif>Kota</option>
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-8">
					<select name="category_ids" class="form-control select2" multiple="multiple" data-placeholder="Pilih kategori" id="categories">
						@foreach($categories as $category)
							<option value="{{ $category['id'] }}" @if(!empty(request('category_ids')) && in_array($category['id'], request('category_ids'))) selected @endif>
								{{ $category['category_name'] }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4">
					<a name="filter" id="filter" class="btn btn-primary">Filter</a>
					@if(Route::current()->getName() == "admin.project.getSuccessSupporter")
						@if (isPermitted('admin.project.getSuccessSupporterExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.project.getPendingSupporter")
						@if (isPermitted('admin.project.getPendingSupporterExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@elseif(Route::current()->getName() == "admin.project.getExpiredSupporter")
						@if (isPermitted('admin.project.getExpiredSupporterExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
					@endif
					<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
				</div>
			</div>
		</form>
		<br><br>
		<h2 class="pull-left">Total Infak Terikat {{ priceFormat($total) }}</h2>
		<br><br><br><hr>
			<p class="text-right">{{ $count }} transaksi ditemukan</p>
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Pemberi Infak</th>
						<th>Nama Project/Campaign</th>
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
          @foreach($supporters as $item)
					<tr>
						<td>{{ $item['fullname'] }}</td>
						<td>{{ $item['project']['title'] }}</td>
						<td>
							@if ($item['reward_id'])
							<div>
								<label>Order : </label><br>
								@foreach($item->details as $row)
								{{ $row['item'].'('.priceFormat($row['price']).' x '.$row['quantity'].'). Atas nama: '.$row['name'] }}<br>
								@endforeach
							</div>
							@endif
							<div>
								<label>Nominal : </label> {{ priceFormat($item['unique_code'] ? $item['money'] + $item['unique_code'] : $item['money']) }}
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
						<td>{{ $item['unique_code'] ? $item['amount'] + $item['unique_code'] : '-' }}</td>
						<td>{{ formatTime($item['created_at'], 'd F Y, H:i') }}</td>
						<td>
							@if (isPermitted('admin.project.submitNote'))
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
								@if (isPermitted('admin.project.confirmCheck'))
									Belum Dicek <br>
									<button class="btn btn-success btn-sm" onclick="confirm_check('{{ $item->id }}')" >Confirm Check</button>
								@endif
							@else
								@if (isPermitted('admin.project.cancelCheck'))
									Sudah Dicek <br>
									<button class="btn btn-danger btn-sm" onclick="cancel_check('{{ $item->id }}')" >Cancel Check</button>
								@endif
							@endif
						</td>
						<td>
							@if (isPermitted('admin.project.putRejectSupporter'))
								{!! Form::link("Reject", 'PUT', route('admin.project.putRejectSupporter', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}
							@endif
							@if (isPermitted('admin.project.putAcceptSupporter'))
								{!! Form::link("Accept", 'PUT', route('admin.project.putAcceptSupporter', ['id' => $item->id]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}
							@endif
						</td>
					</tr>
          @endforeach
				</tbody>
			</table>
      <div class="text-right" style="margin-right: 30px;">
        {!! $supporters->render() !!}
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
			url: '/backend/project/confirm_check/' + id,
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
			url: '/backend/project/cancel_check/' + id,
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
			url: '/backend/project/submit_note',
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
