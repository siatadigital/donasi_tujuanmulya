@section('head')
	<link rel="stylesheet" href="{{ asset('css/user-setting.css') }}">
	<link rel="stylesheet" href="{{ asset('css/user-show.css') }}">
	<script src="{{ asset('js/user-setting.js') }}"></script>
@stop

<div class="progress small-custom" id="progressglobal">
	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
	<span class="sr-only">0% Complete</span>
	</div>
</div>

@if(Auth::check() && Auth::user()->id == $user->id)
	<div class="hidden">
		<input type="file" id="browse-image" name="avatar">
	</div>
@endif

<div class="container-mobile">
	<div class="" style="padding: 20px;">
		@if(Auth::check() && Auth::user()->id == $user->id)
			<div class="user-avatar-block">
				<img id="avatar-preview" src="{{ media($user['avatar'], 'small') }}" alt="{{ $user['name'] }}" class="img-rounded">
				<button id="change-avatar">
					<i class="fa fa-camera"></i>
				</button>
			</div>
		@else
			<div class="user-avatar-block">
				<img id="avatar-preview" src="{{ media($user['avatar'], 'small') }}" alt="{{ $user['name'] }}" class="img-rounded">
			</div>
		@endif

		<div class="description-block">
			<header>
				<h2>{{ $user['name'] }}</h2>
				<p>{{ "@" . $user['username'] }}</p>
			</header>
			<nav class="social-account">
				@if($user['facebook'])
					<a href="{{ $user['facebook'] }}" class="fa fa-facebook" target="_blank"></a>
				@endif
				@if($user['twitter'])
					<a href="{{ $user['twitter'] }}" class="fa fa-twitter" target="_blank"></a>
				@endif
				@if($user['instagram'])
					<a href="{{ $user['instagram'] }}" class="fa fa-instagram" target="_blank"></a>
				@endif
				@if($user['youtube'])
					<a href="{{ $user['youtube'] }}" class="fa fa-youtube" target="_blank"></a>
				@endif
				@if($user['soundcloud'])
					<a href="{{ $user['soundcloud'] }}" class="fa fa-soundcloud" target="_blank"></a>
				@endif
			</nav>
			<p>
				@if($user['is_verified'])
					<span class="label label-success">Verified</span>
				@else
					<span class="label label-default">Not verified</span>
				@endif
			</p>
		</div>
	</div>
</div>

<section class="navigation-user" style="padding: 0 20px;">
	<div class="container-mobile">
		<ul class="nav nav-pills">
			<li role="presentation" @if(segment(3) == '') class="active" @endif>
				<a href="{{ route('user.getShow', $user['username']) }}">Info</a>
			</li>
			@if($user->is_verified == 1)
				<li role="presentation" @if(segment(3) == 'projects') class="active" @endif>
					<a href="{{ route('user.getProjects', $user['username']) }}">
						Campaign
					</a>
				</li>
			@endif
			@if (auth()->user())
				@if($user->id == auth()->user()->id)
				<li role="presentation" @if(segment(3) == 'support') class="active" @endif>
					<a href="{{ route('user.getSupport', $user['username']) }}">
						Donatur
					</a>
				</li>
				@endif
			@endif
		</ul>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crop image</h4>
      </div>
      <div class="modal-body">
				<div>
        	<img src="" alt="" id="avatar-crop">
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
