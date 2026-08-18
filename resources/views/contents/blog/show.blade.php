@extends('layouts.default')

@section('head')
	<meta name="description" content="{{ $blog['summary'] }}" />

	<meta property="og:url" content="{{ route('blog.getShow', $blog['slug']) }}" />
	<meta name="author" content="{{ $blog['description'] }}">
	<meta property="og:title" content="{{ $blog['title'] }}" />
	<meta property="og:description" content="{{ $blog['description'] }}" />
	<meta property="og:type" content="article" />
	<meta property="og:image" content="{{ media($blog->cover,'medium') }}" />

	<meta name="twitter:site" content="@PeduliIndonesia">
	<meta name="twitter:title" content="{{ $blog['title'] }}">
	<meta name="twitter:description" content="{{ $blog['description'] }}">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="{{ media($blog->cover,'medium') }}">

	<style>
		article img{
			max-width: 100%;
			margin-bottom:30px;
		}
		article{
			font-size: 16px;
		}
	</style>
@stop
@section('content')
	<div class="container-mobile story-detail">
			<article style="padding: 20px;">
				<header class="page-header text-center">
					<h1>{{ $blog['title'] }}</h1>
					@if(auth()->user())
						@if(auth()->user()->id == $blog['user_id'])
							<a href="{{ route('blog.getEdit', $blog['slug']) }}">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
						@endif
					@endif
					<p class="blog-info">
						<span style="margin-right: 10px;">
							<i class="fa fa-user"></i> <a href="{{ route('user.getShow', $blog['user']['username']) }}">{{ $blog['user']['name'] }}</a>
						</span>
						<span style="margin-right: 10px;">
							<i class="fa fa-calendar"></i> {{ date('d F Y', strtotime($blog['created_at'])) }}
						</span>
						<span>
							<i class="fa fa-eye"></i> {{ $count_hit }}
						</span>
					</p>
				</header>

				@if($blog['cover'])
					<img src="{{ media($blog['cover'], 'medium') }}" alt="photo" style="margin: 0 auto 20px;width: 100%;" />
				@endif

				<section style="min-height: 300px;">

					{!! $blog['content'] !!}

				</section>
				<hr>
				<p class="small-label">Bagikan ke Teman</p>
				<div class="detail-share-logo-wrapper padding-top-5">
						<a type="button" style="color: #4267b2;font-size: 21px;padding-right: 10px;" class="btn-share" href="https://www.facebook.com/sharer/sharer.php?u={{ Request::fullUrl() }}&quote={{ $blog['title'] }}%0a{{ substr(strip_tags($blog['content']), 0, 100).'...' }}%0a%0aBaca selengkapnya dengan klik:" target="_blank">
							<i class="fa fa-facebook-square padding-right-10 share-icon"></i>
						</a>
						<a type="button" style="color: #30b042;font-size: 21px;padding-right: 10px;" class="btn-share" href="https://api.whatsapp.com/send?text={{ $blog['title'] }}%0a{{ substr(strip_tags($blog['content']), 0, 100).'...' }}%0a%0aBaca selengkapnya dengan klik:%0a{{ Request::fullUrl() }}" target="_blank">
							<i class="fa fa-whatsapp padding-right-10 share-icon"></i>
						</a>
						<a type="button" style="color: #1ca1f2;font-size: 21px;padding-right: 10px;" class="btn-share" href="https://twitter.com/intent/tweet?text={{ $blog['title'] }}%0a{{ substr(strip_tags($blog['content']), 0, 100).'...' }}%0a%0aBaca selengkapnya dengan klik:%0a{{ Request::fullUrl() }}" target="_blank">
							<i class="fa fa-twitter padding-right-10 share-icon"></i>
						</a>
				</div>
				<br><br>
				<div class="fb-comments" data-href="{{ request()->fullUrl() }}" data-width="100%" data-numposts="5"></div>
				<br><br>
				<h3>Rekomendasi Artikel</h3>
				<br>
				@include('contents.blog._list_artikel')
			</div>
		</article>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'blog',
			content_name: "{{ $blog['title'] }}",
			content_ids: ["{{ $blog['slug'] }}"],
			contents: [{'id': "{{ $blog['slug'] }}", 'quantity': 0}]
		});
	</script>
@stop
