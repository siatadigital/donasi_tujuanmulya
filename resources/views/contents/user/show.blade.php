@extends('layouts.default')
@section('content')
	@include('contents.user._cover')
	<div class="container-mobile" style="padding: 0 20px;">
		<div class="row">
			<div class="col-sm-12">
				@if($user['bio'])
					<header>
						<h3>Informasi Profil</h3>
					</header>
					{!! $user['bio'] !!}
				@else
					<header>
						<h3>Biografi</h3>
					</header>
					Belum diisi
				@endif
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'user_profile_page',
			search_string: "{{ $user->username }}",
		});
	</script>
@stop
