@foreach($projects as $key => $proj)
	<div class="galang-dana-box">
		<div class="row">
			<div class="col-xs-4 col-md-4 pr-0">
				<div class="galang-dana-box-img-container">
					<div class="galang-dana-opacity-box">
						<a href="{{ route('project.newGetShow', $proj['slug']) }}" class="btn btn-transparent">
							Lihat Detail
						</a>
					</div>
					<img src="{{ media($proj['cover'],'medium') }}" class="galang-dana-box-img" >
				</div>
			</div>
			<div class="col-xs-8 col-md-8 pl-0">
				<div class="galang-dana-box-text">
					<div class="galang-dana-content-wrapper ellipsis-title">
							<a href="{{ route('project.newGetShow', $proj['slug']) }}" class="medium-title">{{ $proj['title'] }}</a>
					</div>
					<div class="galang-dana-profile-wrapper">
						<div class="galang-dana-name-profile">
							<span>{{ $proj['user']['name'] }}</span>
							@if($proj['user']['is_verified'])
							<div class="icon-check" style="margin-left:5px;">
								<i class="fa fa-check"></i>
							</div>
							@endif
							@if($proj['user']['type_akun'] === 'Lembaga')
							<div class="icon-org" style="margin-left:5px;">
								<span>ORG</span>
							</div>
							@endif
						</div>
					</div>
					<div class="progress-bar"><div class="progress-bar-active progress-bar-striped bg-info" style="width: {{ $proj['progress'] }}%"></div> </div>
					<div style="display: flex;justify-content: space-between;margin-top: 15px;">
						<div>
							<div class="dana-terkumpul-title-wrapper">
								<p class="small-title-bold">{{ trans('homepage.terkumpul') }}</p>
							</div>
							<div class="dana-terkumpul-wrapper">
								<p class="price-title-blue">{{ priceFormat($proj['money_progress']) }}</p><div class="space"></div>
							</div>
						</div>
						<div>
							<div class="dana-terkumpul-title-wrapper text-right">
								<p class="small-title-bold">{{ trans('homepage.sisa_hari') }}</p>
							</div>
							<div class="dana-terkumpul-wrapper" style="justify-content: flex-end;">
								<p class="price-title-blue">{{ intval((strtotime($proj['time_end']) - Time()) / 86400) < 0 ? 'Kadaluwarsa' : intval((strtotime($proj['time_end']) - Time()) / 86400) }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endforeach
