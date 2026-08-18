@extends('layouts.default')
@section('title',trans('news.title'))
@section('head')
<link rel="stylesheet" href="{{ asset('css/homepage1.4.css') }}">
@stop
@section('content')
<style>
	.media-body,
	.media-left,
	.media-right {
		display: table-cell;
		vertical-align: middle;
	}

	.media,
	.media-body {
		overflow: hidden;
		zoom: 1;
		margin-bottom: 20px;
	}

	hr.section {
		margin: 0px;
		border: 5px solid #eee;
	}

	a.link_media {
		text-decoration: none
	}
</style>
<section class="stories prisec" style="padding-left:40px">
	<div class="container-mobile">
		@if(auth()->guest())
		<a href="{{ url('auth/register') }}" class="link_media">
			<div class="media" style="margin-top:10px">
				<div class="media-left">
					<img src="{{ url('images/account.png') }}" alt="gambar" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Daftar
				</div>
			</div>
		</a>
		<hr>
		<a href="{{ url('auth/login') }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/user.png') }}" alt="gambar2" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Masuk
				</div>
			</div>
		</a>
		@else
		<a href="{{ route('user.getShow', ['username'=>auth()->user()->username]) }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/user.png') }}" alt="gambar2" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Akun Profil
				</div>
			</div>
		</a>
		<a href="{{ route('user.getSetting', ['username'=>auth()->user()->username]) }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/settings.png') }}" alt="gambar2" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Pengaturan
				</div>
			</div>
		</a>
		@if(auth()->user())
		@if(auth()->user()->is_internal)
		<a href="{{ route('user.getReportAffiliate') }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/help.png') }}" alt="gambar2" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Laporan Affiliate
				</div>
			</div>
		</a>
		@endif
		@endif
		<a href="{{ route('auth.getLogout') }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/logout.png') }}" alt="gambar2" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Keluar Akun
				</div>
			</div>
		</a>
		@endif
	</div>
</section>
<hr class="section">
<section class="stories prisec" style="padding-left:40px">
	<div class="container-mobile">
		<a href="{{ route('page.getBantuan') }}" class="link_media">
			<div class="media" style="margin-top:10px">
				<div class="media-left">
					<img src="{{ url('images/help.png') }}" alt="gambar3" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Bantuan
				</div>
			</div>
		</a>
		<hr>
		<a href="{{ route('page.getTentang') }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/information.png') }}" alt="gambar4" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Tentang Kami
				</div>
			</div>
		</a>
		<hr>
		<a href="{{ route('page.getSyarat') }}" class="link_media">
			<div class="media">
				<div class="media-left">
					<img src="{{ url('images/terms.png') }}" alt="gambar5" class="img-responsif media-object" width="30">
				</div>
				<div class="media-body" style="padding-left:10px">
					Syarat dan Ketentuan
				</div>
			</div>
		</a>
		<hr>
	</div>
</section>
@stop

@section('scripts')
<script>
	fbq('track', 'ViewContent', {
		content_name: 'akun',
	});
</script>
@stop