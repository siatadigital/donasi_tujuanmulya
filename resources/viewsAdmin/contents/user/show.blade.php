@extends('admin::layouts.default')

@section('content')

	<div class="box box-default">
		<div class="box-header with-border">
			<i class="fa fa-user"></i>
			<h3 class="box-title">{{ $user['name'] }}</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<img src="{{ media($user['avatar'], 'medium') }}" class="img-circle" style="width: 100%;" />
				</div>
				<div class="col-md-9">

					<div class="clearfix">
						<div class="pull-left">
							Status : 
							@if($user['is_verified'] == 0)
								@if($user['is_superadmin'] == 1)
								<b>Admin</b>
								@else
								<b>Member</b>
								@endif
							@elseif($user['is_verified'] == 1)
								@if($user['is_superadmin'] == 1)
								<b>Verified Admin</b>
								@else
								<b>Verified Member</b>
								@endif
							@else
								<b>Pending</b>
							@endif
						</div>

						<div class="pull-right">
							@if (isPermitted('admin.user.putVerifyAccept'))
							{!! Form::link('Set as Verified Member', 'PUT', route('admin.user.putVerifyAccept', [$user['id']]), ['icon' => 'fa fa-angle-up', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}
							@endif
							or
							@if (isPermitted('admin.user.putVerifyReject'))
							{!! Form::link('Set as Member', 'PUT', route('admin.user.putVerifyReject', [$user['id']]), ['icon' => 'fa fa-angle-down', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
							@endif
						</div>
					</div>
					<hr>

					<table class="table table-striped">
						<tbody>
							<tr>
								<th style="width: 150px;">Alamat Email</th>
								<td>{{ $user['email'] }}</td>
							</tr>
							<tr>
								<th>Nama Pengguna</th>
								<td>{{ $user['username'] }}</td>
							</tr>
							<tr>
								<th>Ulang Tahun</th>
								<td>{{ date('d-m-Y',strtotime($user['birth_date'])) }}</td>
							</tr>
							<tr>
								<th>Jenis Kelamin</th>
								<td>{{ $user['gender'] }}</td>
							</tr>
							<tr>
								<th>Bio</th>
								<td>{!! $user['bio'] !!}</td>
							</tr>
							<tr>
								<th>Provinsi</th>
								<td>{{ $user['province'] }}</td>
							</tr>
							<tr>
								<th>Kota</th>
								<td>{{ $user['city'] }}</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td>{{ $user['address'] }}</td>
							</tr>
							<tr>
								<th>No. WhatsApp</th>
								<td>{{ $user['phone'] }}</td>
							</tr>
							<tr>
								<th>Twitter</th>
								<td>{{ $user['twitter'] }}</td>
							</tr>
							<tr>
								<th>Facebook</th>
								<td>{{ $user['facebook'] }}</td>
							</tr>
							<tr>
								<th>Quotes</th>
								<td><blockquote>{{ $user['quotes'] }}</blockquote></td>
							</tr>
							<tr>
								<th>Foto KTP</th>
								<td><img src="{{ media($user['fotoktp'], 'medium') }}" class="img-responsive" width="200"></td>
							</tr>
							<tr>
								<th>Foto Bersama KTP</th>
								<td><img src="{{ media($user['foto_with_ktp'],'medium') }}" class="img-responsive" width="200"></td>
							</tr>
						</tbody>
					</table>
					<div class="pull-right">
						@if (isPermitted('admin.user.putAsAdmin'))
							@if($user['id'] > 1 and $user['is_verified'] == 1)
								@if($user['is_superadmin'] == 1)
								{!! Form::link('Lepas dari Admin', 'PUT', route('admin.user.putAsAdmin', [$user['id']]), ['icon' => 'fa fa-angle-down', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
								@else
								{!! Form::link('Jadikan Admin', 'PUT', route('admin.user.putAsAdmin', [$user['id']]), ['icon' => 'fa fa-angle-up', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}
								@endif
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

@stop