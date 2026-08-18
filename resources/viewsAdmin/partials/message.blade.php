@if ( ! empty(session('message')))
	<div class="alert alert-{{ session('message.type') }}">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

		<strong>{!! session('message.title') !!}</strong>

		@if(!empty(session('message.content')))
			@if(is_array(session('message.content')))
				<ul>
					@foreach (session('message.content') as $m)
						<li>{{ $m }}</li>
					@endforeach
				</ul>
			@else
				<p>{!! session('message.content') !!}</p>
			@endif
		@endif
	</div>
@endif
