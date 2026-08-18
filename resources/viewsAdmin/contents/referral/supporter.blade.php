@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
				@if(Route::current()->getName() == "admin.referral.getProject")
					<form action="{{ route("admin.referral.getProjectExport") }}" method="post">
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
							<option value="Nama Referrer" @if(request('type_cari') == 'Nama Referrer') selected @endif>Nama Referrer</option>
							<option value="Kode Referrer" @if(request('type_cari') == 'Kode Referrer') selected @endif>Kode Referrer</option>
							<option value="Nama Campaign" @if(request('type_cari') == 'Nama Campaign') selected @endif>Nama Campaign</option>
							<option value="Nama Pemberi Infak" @if(request('type_cari') == 'Nama Pemberi Infak') selected @endif>Nama Pemberi Infak</option>
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
						@if(Route::current()->getName() == "admin.referral.getProject")
							@if (isPermitted('admin.referral.getProjectExport'))
							<input type="submit" id="export" value="Export Excel" class="btn btn-success">
							@endif
						@endif
						<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
					</div>
				</div>
      </form>
			<h2 class="pull-left">Total Infak Terikat {{ priceFormat($total) }}</h2>
			<br><br><br><hr>
			<p class="text-right">{{ $count }} transaksi ditemukan</p>
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Nama Referrer</th>
						<th>Kode Referral</th>
						<th>Nama Campaign</th>
						<th>Nama Pemberi Infak</th>
						<th>Bank</th>
						<th>No. WhatsApp</th>
						<th>Nominal</th>
						<th>Tanggal Transaksi</th>
					</tr>
        </thead>
        <tbody>
          @foreach($supporters as $item)
					<tr>
						<td>{{ $item['referrer_name'] }}</td>
						<td>{{ $item['code_referral'] }}</td>
						<td>{{ $item['project_title'] }}</td>
						<td>{{ $item['fullname'] }}</td>
						<td>{{ ($item['data_payment_method'] ? $item['data_payment_method'] : '') }}</td>
						<td>{{ $item['phone'] }}</td>
						<td>{{ priceFormat($item['money']) }}</td>
						<td>{{ $item['created_at'] }}</td>
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
@stop
