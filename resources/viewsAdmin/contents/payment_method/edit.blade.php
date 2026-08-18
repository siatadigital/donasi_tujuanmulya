@extends('admin::layouts.default')
@section('head')
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
		<form action="{{ route('admin.payment_method.updatePaymentMethod', $data['id']) }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
				<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br><br>
							@if(!empty($data->logo))
                            <div class="col-md-12">
                                <div class="form-group">
                                    <img src="{{ asset('images/payment_methods/'.$data->logo) }}" width="100" alt="">
                                </div>
                            </div>
							@endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Logo</label>
                                    <input type="file" name='logo' id="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Code</label>
                                    <input type="text" name='' id="" value="{{ $data->code }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Group</label>
                                    <input type="text" name='' id="" value="{{ $data->group->name }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Name</label>
                                    <input type="text" name='name' id="" value="{{ $data->name }}" class="form-control" required>
                                </div>
                            </div>
                            @if ($data->group->name == 'Manual Transfer')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Account Name</label>
                                    <input type="text" name='account_name' id="" value="{{ $data->account_name }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Account Number Zakat</label>
                                    <input type="text" name='account_number_zakat' id="" value="{{ $data->account_number_zakat }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Account Number Infak</label>
                                    <input type="text" name='account_number_infak' id="" value="{{ $data->account_number_infak }}" class="form-control" required>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Status Infak Umum</label>
                                    <select name="is_active_infak" id="" class="form-control">
										@if ($data->is_active_infak)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="0" selected>Not Active</option>
											<option value="1" >Active</option>
										@endif
									</select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Status Zakat</label>
                                    <select name="is_active_zakat" id="" class="form-control">
										@if ($data->is_active_zakat)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="0" selected>Not Active</option>
											<option value="1" >Active</option>
										@endif
									</select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Status Infak Terikat</label>
                                    <select name="is_active_campaign" id="" class="form-control">
										@if ($data->is_active_campaign)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="0" selected>Not Active</option>
											<option value="1" >Active</option>
										@endif
									</select>
                                </div>
                            </div>
						</div>
					</div>
				</div>
				<br>
				<div class="box-footer text-center">
                        <button type="submit" name="submit" class="btn btn-success">Save</button>
						<a class="btn btn-warning" href="{{ route('admin.payment_method.getPaymentMethod') }}">Cancel</a>
                </div>
		</form>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
