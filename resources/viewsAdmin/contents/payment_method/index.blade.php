@extends('admin::layouts.default')

@section('content')

	{{-- <h1>List Blog</h1> --}}

	<div class="nav-tabs-custom">

		{{-- @include('admin::contents.category._tab') --}}

		<div class="tab-content">
			@if (isPermitted('admin.payment_method.getCreate'))
			<a href="{{ url('/backend/payment_method/create') }}" class="btn btn-success pull-right">Tambah Manual Transfer</a><br><br><br>
			@endif
			<div class="clearfix"></div>
			<br>

			{!! $data->render() !!}

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Group</th>
						<th>Logo</th>
						<th>Name</th>
						<th>Account Name</th>
						<th>Account Number Zakat</th>
						<th>Account Number Infak</th>
						<th>Status Infak Umum</th>
						<th>Status Zakat</th>
						<th>Status Infak Terikat</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $data->isEmpty() )
				@foreach($data as $u)
					<tr>
						<td>
							{{ $u['group']['name'] }}
						</td>
						<td>@if ($u['logo']) <img src="{{ asset('images/payment_methods/'.$u['logo']) }}" width="50" alt="{{ $u['name'] }}" /> @else - @endif</td>
						<td>{{ $u['name'] }}</td>
						<td>{{ $u['account_name'] }}</td>
						<td>{{ $u['account_number_zakat'] }}</td>
						<td>{{ $u['account_number_infak'] }}</td>
						<td>{{ $u['is_active_infak'] ? 'Active' : 'Not Active' }}</td>
						<td>{{ $u['is_active_zakat'] ? 'Active' : 'Not Active' }}</td>
						<td>{{ $u['is_active_campaign'] ? 'Active' : 'Not Active' }}</td>
						<td>
							@if (isPermitted('admin.payment_method.getEdit'))
							<a href="{{ route('admin.payment_method.getEdit', $u['id']) }}" class="btn btn-info btn-sm">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
							@endif
						</td>
					</tr>
				@endforeach
				@else
					<tr>
						<td colspan="5" class="text-center">
							No data
						</td>
					</tr>
				@endif

				</tbody>
			</table>

			{!! $data->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
