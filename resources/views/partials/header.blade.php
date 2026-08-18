<nav class="navbar navbar-default navbar-primary navbar-fixed-top">
	<div class="container-mobile">
		<div class="row">
			<div class="col-xs-5 col-md-5">
				<div class="navbar-header">
					<a href="{{ route('page.getIndex') }}" class="navbar-brand">
						<img src="{{ asset('images/logo_n3.png') }}" alt="peduli" width="100%" class="pull-left mx-auto">
					</a>
				</div>
			</div>
			<div class="col-xs-7 col-md-7 pl-0">
				<form class="navbar-form navbar-left" onsubmit="document.location.href='{{ route('user.getSearch', null) }}/' + $('#keyword').val(); return false;">
					{{ csrf_field() }}
					<div class="input-group" id="search-form">
						{!! Form::text('keyword', @request()->segment(1) == 'search' ? urldecode(request()->segment(2)) : '', ['class' => 'form-control', 'placeholder' => trans('header.search_placeholder'), 'id' => 'keyword', 'required']) !!}
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form>
			</div>
			<!-- <div class="col-xs-2 col-md-2 pl-0 pt-1">
				@if (app()->getLocale() == 'en')
				<a href="{{ url('locale/id') }}">
					<img src="{{ asset('images/indonesia.png') }}" width="32" />
				</a>
				@elseif (app()->getLocale() == 'id')
				<a href="{{ url('locale/en') }}">
					<img src="{{ asset('images/english.png') }}" width="32" />
				</a>
				@endif
			</div> -->
		</div>
	</div>
</nav>