<div class="grid-item">
	<article>
		<div class="inside">
			<div class="date">
				{{ date("d m Y",strtotime($event["schedule"])) }}
			</div>
			<a href="{{ route('event.getShow',$event->slug) }}">
				<img src="{{ media($event->cover,'medium') }}" alt="" class="img-responsive">
			</a>
			<header>
				<h3>
					<a href="{{ route('event.getShow',$event->slug) }}">
						{!! $event->title !!}
					</a>
				</h3>
				<p class="text-danger">
					HTM : {{ priceFormat($event->htm) }}
				</p>
			</header>
			<section>
				<p>
					{{ substr($event->description,0,100) }} ..
				</p>
			</section>
			<div class="text-info">
				<small>
					<i class="fa fa-map"></i> {{ $event->location }}
				</small>
			</div>
		</div>
	</article>
</div>