<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.structure.min.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.theme.min.css') }}">
<script src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<section id="step2" style="display:none;">
	<header class="page-header">
		<h1>Tambahkan Opsi Pilihan</h1>
		<p class="display">Jika anda memiliki opsi pilihan, silahkan cantumkan dan jelaskan tentang opsi pilihan tersebut.</p>
	</header>
	<div class="row" style="padding-bottom:50px;">
		<div class="col-xs-8">
			<div id="reward-form">
				<div class="form-group">
					<label for="reward">Opsi Pilihan</label>
					{!! Form::text('reward','Rp 50.000',['class'=>'form-control input-lg','id'=>'reward','ui-money-mask','ng-model'=>'reward_money','ui-mask'=>'50.000']) !!}
					<small>Batas Opsi Pilihan adalah diatas Rp. 50.000</small>
				</div>
				<div class="form-group">
					<label for="max_name_count">Opsi Pilihan Max Input Nama</label>
					<input name="max_name_count" id="max_name_count" value="1" type="number" class="form-control" />
				</div>
				<div class="form-group">
					<label for="description">Opsi Pilihan Deskripsi</label>
					<textarea name="description" id="description" style="height:100px;" class="form-control" ></textarea>
				</div>

				<div class="form-group">
					<label for="cover">Foto Opsi Pilihan</label>
					<div class="input-group">
						{!! Form::text('thumbnail', null,['class'=>'form-control','placeholder'=>'Upload foto Paket Investasi yang diberikan untuk dukungan sejumlah diatas.','readonly','id'=>'thumbnail']) !!}

						<span class="input-group-btn">
							<button type="button" id="btn-browse-thumbnail" class="btn btn-default">
								Browse Image
							</button>
						</span>
					</div>
				</div>

				<div class="progress" style="display:none" id="progress_thumbnail">
					<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					</div>
				</div>

				<div class="hide">
					<input type="file" id="browse-thumbnail">
				</div>

				<div class="text-left">
					<button type="button" id="add-reward" class="btn btn-primary">
						Add Opsi Pilihan
					</button>
					<button type="button" id="reset-form" class="btn btn-default" style="display:none;">
						Buat Baru
					</button>
				</div>
			</div>
			<br>
			<div class="form-group">
				<div class="chekbox">
					<label>
						<input type="checkbox" name="noreward" id="noreward"> Galang Dana ini tanpa Opsi Pilihan.
					</label>
				</div>
			</div>

			<script>
				$(function() {
					$("#noreward").on("change", function() {
						if($(this).is(':checked') == true) {
							$("#reward-form").fadeOut(200);
							$("#finish").attr("disabled",false);
						}else{
							$("#reward-form").fadeIn(200);
						}
					})
				})
			</script>

			<br><br>
			<button type="submit" id="finish" class="btn btn-primary" @if(! @$project) disabled @endif >Simpan & Ajukan Galang Dana</button>
		</div>

		<div class="col-xs-4">
			<div id="reward-list">
				@if(@$project['rewards'])
				@foreach($project['rewards'] as $rew)
					<div
						class="rewards"
						data-id="{{ $rew['id'] }}"
						data-price="{{ priceFormat($rew['price']) }}"
						data-cover="{{ $rew['cover'] }}"
						data-content="{{ $rew['content'] }}"
					>
						<input type="hidden" name="rewards[id][]" value="{{ $rew['id'] }}" />
						<input type="hidden" name="rewards[price][]" value="{{ priceFormat($rew['price']) }}" class="form-control" />
						<input type="hidden" name="rewards[cover][]" value="{{ $rew['cover'] }}" class="form-control" />
						<input type="hidden" name="rewards[content][]" value="{{ $rew['content'] }}" class="form-control" />
						<input type="hidden" name="rewards[max_name_count][]" value="{{ $rew['max_name_count'] }}" class="form-control" />
						<input type="hidden" name="rewards[is_new][]" value="0" class="form-control" />

						<header>
							<h4 class="rewards-price">{{ priceFormat($rew['price']) }}</h4>
							<h6 class="rewards-max_name_count">Max {{ $rew['max_name_count'] }} Input Nama</h6>
						</header>
						@if($rew['cover'])
							<img style="margin-bottom: 5px;" src="{{ media($rew['cover'], 'small') }}" class="rewards-cover img-responsive"/>
						@endif
						<p style="margin: 0 0 5px;" class="rewards-content">{{ $rew['content'] }}</p>
						<button type="button" class="btn btn-warning edit-reward"><i class="fa fa-pencil"></i></button>
						<button type="button" class="btn btn-danger remove-reward"><i class="fa fa-trash"></i></button>
					</div>
				@endforeach
				@endif
			</div>
		</div>
	</div>
</section>

<section id="step1">
	<header class="page-header">
		<h1>{{ trans('create_project.title_head') }}</h1>
		<!-- <p class="display">
			{{ trans('create_project.desc_head') }}
		</p> -->
	</header>
	<div class="row">
		<div class="col-xs-12">
			<header>
				<h4>{{ trans('create_project.preview') }}</h4>
			</header>
			<div class="paper-block">
				<div class="block-item-paper">
					<div class="cover thumbnail-project" id="cover-preview" style="background-image:url({{ media(@$project['cover'], 'small') }})">

					</div>
					<article class="paper-inside">
						<section class="content">
							<header>
								<h4>
									<a id="title-preview">{{ Input::old('title', @$project['title']) }}</a>
								</h4>
								<p>{{ trans('create_project.oleh') }} <a id="user-preview">{{ $auth['name'] }}</a></p>
							</header>
							<section class="explore-summary">
								<p id="summary-preview">{{ Input::old('summary', @$project['summary']) }}</p>
							</section>
						</section>
					</article>
				</div>
			</div>

			<div id="video-campaign-preview" @if(! Input::old('video', @$project['video'])) style="display:none" @endif>
				<header>
					<h4>{{ trans('create_project.video_preview') }}</h4>
				</header>
				<div class="paper-block">
					<iframe width="100%" height="200" id="video-preview" src="{{ Input::old('video', @$project['video']) }}" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			@if (Auth::user())
				@if(Auth::user()->is_superadmin == '1')
				<div class="form-group">
					<label for="user_id">User</label>
					<select id="user_id" name="user_id" class="form-control">
						@if (Route::currentRouteName() == 'project.getCreate')
							@foreach($users as $item)
								<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
						@endif
						@if (Route::currentRouteName() == 'project.getEdit')
							@foreach($users as $item)
								@if ($item->id == $project->user_id)
									<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
								@else
								<option value="{{ $item->id }}">{{ $item->name }}</option>
								@endif
							@endforeach
						@endif
					</select>
				</div>
				@endif
			@endif

			@if(Route::current()->getName() == "project.getCreate")
			<!-- <div class="form-group">
				<label for="title">{{ trans('project.induk_galang_dana') }}</label>
				<select name="fundraiser_project_id" class="form-control" id="fundraiser">
					<option value="">{{ trans('project.pilih_induk_galang_dana') }}</option>
					@foreach ($projects as $project)
					<option 
						value="{{ $project->id }}"
						data-title="{{ $project->title }}"
						data-cover="{{ $project->cover }}"
						data-summary="{{ $project->summary }}"
						data-video="{{ $project->video }}"
						data-money="{{ $project->money_target }}"
						data-content="{{ $project->content }}"
						data-start="{{ $project->time_start }}"
						data-end="{{ $project->time_end }}"
						data-category="{{ $project->category_id }}"
						data-province="{{ $project->provinsi_id }}"
						data-city="{{ $project->kota_id }}"
					>
						{{ $project->title }}
					</option>
					@endforeach
				</select>
			</div> -->
			@endif

			<div id="project-parent">
				<div class="form-group">
					<label for="title">{{ trans('create_project.name') }}</label>
					{!! Form::text('title', isset($short['title'])?$short['title']:null,['class'=>'form-control','required','autofocus','placeholder'=>trans('create_project.name_placeholder'),'id'=>'title']) !!}
				</div>

				<section>
					@if(Route::current()->getName() == "project.getCreate")
						<label for="slug">Custom Slug <small>(opsional)</small></label>
						<div class="input-group">
							<span class="input-group-addon">yukdonasi.org/</span>
							<input type="text" name="slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
						</div>
					@else
						<label for="slug">Slug</label>
						<div class="input-group">
							<span class="input-group-addon">yukdonasi.org/</span>
							<input type="text" name="slug" value="{{ $project['slug'] }}" class="form-control" readonly>
						</div>
						<br>
						<label for="slug">Edit Custom Slug <small>(opsional)</small></label>
						<div class="input-group">
							<span class="input-group-addon">yukdonasi.org/</span>
							<input type="text" name="edit_slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
						</div>
					@endif
				</section>
				<br>
				<div class="form-group">
					<label for="cover">{{ trans('create_project.cover') }}</label>
					<div class="input-group">
						{!! Form::text('cover', null, ['class'=>'form-control','required','placeholder'=>trans('create_project.cover_placeholder'),'readonly','id'=>'cover']) !!}

						<span class="input-group-btn">
							<button type="button" id="btn-browse-cover-project" class="btn btn-default">
								{{ trans('create_project.browse') }}
							</button>
						</span>
					</div>
				</div>

				<div class="progress" style="display:none" id="progress_cover">
					<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					</div>
				</div>

				<div class="hide">
					<input type="file" id="browse-cover-project">
				</div>

				<div class="form-group">
					<label for="summary">{{ trans('create_project.summary') }}</label>
					{!! Form::text('summary', null, ['class' => 'form-control', 'id' => 'summary', 'maxlength' => '120', 'placeholder' => trans('create_project.summary_placeholder'), 'required']) !!}
					<span class="help-block" id="summary_count">120</span>
				</div>

				<div class="form-group">
					<label for="video">{{ trans('create_project.youtube') }}</label>
					<div class="input-group">
						{!! Form::text('video', null,['class'=>'form-control','placeholder'=>trans('create_project.youtube_placeholder'),'id'=>'video']) !!}

						<span class="input-group-btn">
							<button type="button" id="btn-video" class="btn btn-primary">
								Preview
							</button>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label for="goal">{{ trans('create_project.target') }}</label>
						{!! Form::text('money_target', null, ['class'=>'form-control input-lg','id'=>'money','ui-money-mask','ng-model'=>'money','ui-mask'=>'50.000','required'=>'required']) !!}
					<small>{{ trans('create_project.target_placeholder') }}</small>
				</div>

				<div class="form-group">
					<label for="campaign">{{ trans('create_project.description') }}</label>
					<div id="campaign-wrapper">
						{!! Form::textarea('content', null, ['id'=>'campaign','class'=>'summernote']) !!}
					</div>
					<div id="campaign-placeholder"></div>
				</div>

				<div class="form-group">
					<label>{{ trans('create_project.start') }}</label>
					<input class="form-control dpfrom" id="startproject" required="required" name="startproject" type="text" value="{{ $date_start or '' }}">
				</div>

				<div class="form-group">
					<label>{{ trans('create_project.end') }}</label>
					<input class="form-control dpto" id="endproject" required="required" name="endproject" type="text" value="{{ $date_end or '' }}">

				</div>

				<script>
					$(function() {
						$( ".dpfrom" ).datepicker({
								dateFormat: 'dd MM yy',
							gotoCurrent: true,
								changeMonth: true,
								changeYear: true,
								// onSelect: function( selectedDate ) {
								//     $( ".dpto" ).datepicker( "option", "minDate", selectedDate );
								// }
						});
						$( ".dpto" ).datepicker({
								dateFormat: 'dd MM yy',
								changeMonth: true,
								changeYear: true,
								// onSelect: function( selectedDate , instance) {

								//     var minDate = $.datepicker.parseDate(instance.settings.dateFormat, selectedDate, instance.settings)
								//     minDate.setMonth(minDate.getMonth() - 3);
								//     $( ".dpfrom" ).datepicker( "option", "minDate", minDate );
								// }
						});
					})
				</script>

				<div class="form-group">
					<label>{{ trans('create_project.category') }}</label>
					<select name="category" id="category" class="form-control" required>
						<option value="0">{{ trans('create_project.category_select') }}</option>
						@foreach ($category as $item)
						<option value="{{ $item->id }}">{{ $item->category_name }}</option>
						@endforeach
					</select>
				</div>

				<header class="page-header">
					<h3>{{ trans('create_project.title_location') }}</h3>
					<p>{{ trans('create_project.desc_location') }}</p>
				</header>

				<div class="form-group">
					<label>{{ trans('create_project.province') }}</label>
					<select name="province" id="province" class="form-control" required>
						<option value="0">{{ trans('create_project.province_select') }}</option>
						@foreach ($provinsi as $item)
						<option value="{{ $item->id }}">{{ $item->provinsi_name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label>{{ trans('create_project.city') }}</label>
					<select name="city" id="city" class="form-control" required disabled="true">
						<option value="0">{{ trans('create_project.city_select') }}</option>
					</select>
				</div>
			</div>
			@if(Route::current()->getName() == "project.getCreate")
			<!-- <div id="project-fundraiser">
				<div class="form-group">
					<label for="title">{{ trans('create_project.name') }}</label>
					{!! Form::text('title', isset($short['title'])?$short['title']:null,['class'=>'form-control','required','autofocus','placeholder'=>trans('create_project.name_placeholder'),'id'=>'title']) !!}
				</div>

				<div class="form-group">
					<label for="goal">{{ trans('create_project.target') }}</label>
						{!! Form::text('money_target', null, ['class'=>'form-control input-lg','id'=>'money','ui-money-mask','ng-model'=>'money','ui-mask'=>'50.000','required'=>'required']) !!}
					<small>{{ trans('create_project.target_placeholder') }}</small>
				</div>

				<div class="form-group">
					<label for="slug">Custom Slug <small>(opsional)</small></label>
					<input type="text" name="slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
				</div>
				<br>
			</div> -->
			@endif

			@if (Auth::guest())
			<header class="page-header">
				<h3>{{ trans('create_project.title_account') }}</h3>
				<p>{{ trans('create_project.desc_account') }}</p>
			</header>

			<div class="form-group">
				<label for="name">{{ trans('create_project.fullname') }}</label>
				{!! Form::text('name',isset($short['name'])?$short['name']:'',['class'=>'form-control','placeholder'=>trans('create_project.fullname_placeholder'),'required', 'autofocus','id'=>'name']) !!}
			</div>
			<div class="form-group">
				<label for="email">{{ trans('create_project.email') }}</label>
				{!! Form::email('email',isset($short['email'])?$short['email']:'',['class'=>'form-control','placeholder'=>trans('create_project.email_placeholder'),'required']) !!}
			</div>
			<div class="form-group">
				<label for="username">{{ trans('create_project.username') }}</label>
				<div class="input-group">
					{!! Form::text('username','',['class'=>'form-control','placeholder'=>trans('create_project.username_placeholder'),'required','id'=>'username']) !!}
					<span class="input-group-btn">
						<button type="button" id="btn-check-username" class="btn btn-primary">
							<i class="fa fa-refresh"></i> {{ trans('create_project.periksa') }}
						</button>
					</span>
				</div>
				<strong id="alert-username">

				</strong>
			</div>
			<div class="form-group">
				<label for="phone">{{ trans('create_project.phone') }}</label>
				{!! Form::text('phone',isset($short['phone'])?$short['phone']:'',['class'=>'form-control','placeholder'=>trans('create_project.phone_placeholder'),'required', 'autofocus']) !!}
			</div>
			<div class="form-group">
				<label for="password">{{ trans('create_project.password') }}</label>
				<div class="input-row">
					<input type="password" name="password" value="{{ Input::old('password') }}" class="form-control  reveal-password" placeholder="{{ trans('create_project.password_placeholder') }}" required="required">
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
			<div class="form-group">
				<label for="password_confirmation">{{ trans('create_project.password_conf') }}</label>
				<div class="input-row">
					<input type="password" name="password_confirmation" value="{{ Input::old('password_confirmation') }}" class="form-control reveal-password" placeholder="{{ trans('create_project.password_conf_placeholder') }}" required="required">
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
			</div>
			@endif

			<script type="text/javascript">
			@if( isset($select_provinsi) && isset($select_kota) && isset($select_category) )
			$(document).ready(function(){
				$("#username").keyup(function(){
					if($(this).val().indexOf(" ") >= 0)
					{
						$(this).val($(this).val().replace(" ",""));
					}

					// Replace symbols with underscore
					var username = $(this).val();
					if (username.match(/[-!$%^&*()+|~=`{}\[\]:";'<>?,.\/]/g)){
						$(this).val(username.replace(/[-!$%^&*()+|~=`{}\[\]:";'<>?,.\/]/g, "_"));
					}
				});

				var category = {{ $select_category }};
				$("#category option").filter(function() {
				    return $(this).val() == category;
				}).prop('selected', true);

				var id_prov = {{ $select_provinsi }};
				$("#province option").filter(function() {
				    return $(this).val() == id_prov;
				}).prop('selected', true);

				$.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') } });
				var $root = $("meta[name='root-url']").attr('content');
	            $.ajax({
	                url: $root + "/api/v1/getkota",
	                type: 'POST',
	                data: { 'provinsi': id_prov },
	                success: function (data) {
	                    $('#city').html('');
	                    $('#city').append(data);
	                    $('#city').prop('disabled', false);

			            var id_kota = {{ $select_kota }};
						$("#city option").filter(function() {
						    return $(this).val() == id_kota;
						}).prop('selected', true);
	                }
	            });
			});
			@endif

			$(function(){
				$('#province').change(function(){
					$.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') } });
					var $root = $("meta[name='root-url']").attr('content');
		            id_prov = $(this).val();

		            $.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') } });
					var $root = $("meta[name='root-url']").attr('content');
		            $.ajax({
		                url: $root + "/api/v1/getkota",
		                type: 'POST',
		                data: { 'provinsi': id_prov },
		                success: function (data) {
		                    $('#city').html('');
		                    $('#city').append(data);
		                    $('#city').prop('disabled', false);
		                }
		            });
		        });
			})
			</script>

			<div class="text-center">
				<p>
					<a href="#" target="_blank">{{ trans('create_project.syarat_ketentuan') }}</a>
				</p>
				<p>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="accept" id="accept" checked="true" required> {{ trans('create_project.cek_syarat_ketentuan') }}
						</label>
					</div>
				</p>
				<button type="button" class="btn btn-primary" id="btn-save-campaign">{{ trans('create_project.simpan_lanjut') }}</button>
			</div>
		</div>
	</div>
</section>
<br><br>

<script>
	$(document).ready(function() {
		$('#fundraiser').on('change', function() {
			var value = $(this).val();

			if (value) {
				$('#project-parent').hide();
				$('#project-parent').find('input').prop('disabled', true);
				$('#project-fundraiser').show();
				$('#project-fundraiser').find('input').prop('disabled', false);
			} else {
				$('#project-parent').show();
				$('#project-parent').find('input').prop('disabled', false);
				$('#project-fundraiser').hide();
				$('#project-fundraiser').find('input').prop('disabled', true);
			}
		});

		$('#fundraiser').change();
	});
</script>