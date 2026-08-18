@extends('admin::layouts.default')

@section('styles')
<style>
    .check-label {
        font-weight: normal;
        margin-left: 8px;
    }
</style>
@endsection

@section('content')

	<div class="box box-default">
		<div class="box-body">
			<form method="post" action="{{ url('/backend/user/admin/store') }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
							<div class="col-md-6">
                                <div class="user-photo">
                                    <input type="file" name="image" accept="image/*" id="image" style="opacity:0">
                                    <input type="hidden" name="photo" id="photo">
                                    <div class="text-center"> Photo Profil (Max File Size: 1MB)</div>
                                    <div class="upload">
                                        <div class="upload-content">
                                            <img src="{{ url('/images/default.jpg') }}" id="preview-image" width="300">
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Name</label>
                                    <input type="text" name='name' id="name" value="" class="form-control" required>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Username</label>
                                    <input type="text" name='username' id="username" value="" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Email</label>
                                    <input type="email" name='email' id="email" value="" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Password</label>
                                    <div class="input-row">
                                        <input type="password" name='password' id="password" value="" class="form-control reveal-password" required>
                                        <button class="button-input" type="button">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- input no. telepon -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Phone</label>
                                    <input type="number" name='phone' id="phone" value="" class="form-control" required>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Province</label>
                                    <input type="text" name='province' id="province" value="" class="form-control" required>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">City</label>
                                    <input type="text" name='city' id="city" value="" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Alamat Rumah</label>
                                    <textarea name='address' id="address" value="" class="form-control" required></textarea>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Bio</label>
                                    <textarea name='bio' id="bio" value="" class="form-control" required></textarea>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Quotes</label>
                                    <textarea name='quotes' id="quotes" value="" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Internal</label>
                                    <select name="is_internal" class="form-control">
                                        @foreach([0, 1] as $number)
                                        <option value="{{ $number }}">
                                            {{ $number ? 'Ya' : 'Tidak' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="form-group-referral">
                                <div class="form-group">
                                    <label class="control-label mb-10">Kode Referral</label>
                                    <input type="text" name='code_referral' value="{{ $codeReferral }}" class="form-control" required readonly>
                                </div>
                            </div>
							{{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Twitter</label>
                                    <input type="text" name='twitter' id="twitter" value="" class="form-control" >
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Facebook</label>
                                    <input type="text" name='facebook' id="facebook" value="" class="form-control" >
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Group Privilege</label>
                                    <select name="group_privilege_id" class="form-control" id="group-privilege">
                                        <option value="">Pilih Group Privilege</option>
                                        @foreach($groupPrivileges as $groupPrivilege)
                                        <option value="{{ $groupPrivilege->id }}">
                                            {{ $groupPrivilege->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Menu Admin</label>
                                    @foreach($menuAdmins as $menuAdmin)
                                    <div>
                                        <input 
                                            type="checkbox" 
                                            name='menu_admin_ids[]' 
                                            id="menu-admin-{{ $menuAdmin->id }}" 
                                            value="{{ $menuAdmin->id }}"
                                        />
                                        <label
                                            class="check-label" 
                                            for="menu-admin-{{ $menuAdmin->id }}"
                                        >
                                            {{ $menuAdmin->title }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Grafik Dashboard</label>
                                    @foreach($dashboardItems as $dashboardItem)
                                    <div>
                                        <input 
                                            type="checkbox" 
                                            name='dashboard_item_ids[]' 
                                            id="dashboard-item-{{ $dashboardItem->id }}" 
                                            value="{{ $dashboardItem->id }}"
                                        />
                                        <label
                                            class="check-label" 
                                            for="dashboard-item-{{ $dashboardItem->id }}"
                                        >
                                            {{ $dashboardItem->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<br>
				<div class="box-footer text-center">
                        <button type="submit" name="submit" class="btn btn-success">Save</button>
						<a class="btn btn-warning" href="{{ route('admin.user.getIndex') }}">Cancel</a>
                </div>
			</form>
		</div>
	</div>

@stop

@section('scripts')
<script>
$(document).ready(function(){
	$('.upload').click(function(){
            $('.user-photo input[type="file"]').click();
            return false;
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview-image').attr('src', e.target.result);
                    $('#photo').val(e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function(){
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                //get the file size and file type from file input field
                var fsize = $(this)[0].files[0].size;

                if(fsize > 1048576) { //do something if file size more than 1 MB (1048576)
                    alert("Ukuran file terlalu besar");
                    $(this).val('');
                }else {
                    $("#preview-image").css('opacity','1');
                    readURL(this);
                }
            }else{
                alert("Silahkan upgrade browser untuk untuk mendapatkan fitur validasi file max size");
                $("#preview-image").css('opacity','1');
                readURL(this);
            }
        });

    $('select[name=is_internal]').on('change', function() {
        var value = $(this).val();
        var command = Number(value) ? 'show' : 'hide';

        $('#form-group-referral')[command]();
    });

    $('select[name=is_internal]').change();

    $('#group-privilege').on('change', function() {
        var groupPrivilageId = $(this).val();

        if (!groupPrivilageId) {
            $('input[id^=menu-admin-]').prop('checked', false);
            return;
        }

        $.ajax({
            type: "GET",
            url: '{{ route("admin.group_privilege.getJsonGroupPrivilegeDetails") }}',
            data: {
                group_privilege_id: groupPrivilageId,
            },
            success: function(response) {
                var details = response.data;

                $('input[id^=menu-admin-]').prop('checked', false);

                for (var detail of details) {
                    $(`input[id^=menu-admin-][value=${detail.menu_admin_id}]`).prop('checked', true);
                }
            },
            error: function() {
                alert('Gagal mendapatkan detail group privilege');
            }
        });
    });
});
</script>
@stop