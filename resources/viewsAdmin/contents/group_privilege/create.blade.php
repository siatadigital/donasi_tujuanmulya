@extends('admin::layouts.default')

@section('content')

	<div class="box box-default">
		<div class="box-body">
			<form method="post" action="{{ url('/backend/group_privilege/store') }}">
				{{ csrf_field() }}
				<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br><br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Title</label>
                                    <input type="text" name='title' id="title" value="" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Description</label>
                                    <textarea name='description' id="description" value="" class="form-control" required></textarea>
                                </div>
                            </div>
                            <h4>Menu Admin</h4>
                            <div class="col-md-12">
                                @foreach ($menu_admin as $item)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><input type="checkbox" name="menu[]" value="{{ $item->id }}"> {{ $item->title }} </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
				<br>
				<div class="box-footer text-center">
                        <button type="submit" name="submit" class="btn btn-success">Save</button>
						<a class="btn btn-warning" href="{{ route('admin.group_privilege.getGroupPrivilege') }}">Cancel</a>
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
});
</script>
@stop