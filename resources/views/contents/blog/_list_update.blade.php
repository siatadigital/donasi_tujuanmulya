@foreach($blogs_update as $key => $blog)
	<div class="new-activity-wrapper">
		<img src="{{ media($blog['cover'], 'medium') }}" class="new-activity-img">
		<div class="new-activity-text">
			<div class=" ellipsis-title">
						<a href="{{ route('blog.getShow', $blog['slug']) }}" class="activity-title">{{ $blog['title'] }}</a>
			</div>
			<br>
			<i class="fa fa-calendar"></i><span class="space"></span><span class="medium-title-lite">{{ date('d F Y', strtotime($blog['created_at'])) }}</span>
			<br>
			<i class="fa fa-user"></i><span class="space"></span>
			<a href="{{ route('user.getShow', $blog['user']['username']) }}" class="medium-title-lite ellipsis-title">
				{{ $blog['user']['name'] }}
				@if($blog['user']['is_verified'])
				<div style="margin-left: 5px;background: #008797;display:inline-block;width: 16px;height: 16px;border-radius: 50px;text-align: center;"><i class="fa fa-check" style="color: white;font-size: 10px;"></i></div>
				@endif
			</a>


			<br>
			@if($auth and $auth['id'] == $blog['user_id'])
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="{{ route('blog.getEdit', $blog['slug']) }}">
					<i class="fa fa-pencil"></i>
					Edit
				</a>
			@endif
		</div>
	</div>
@endforeach
