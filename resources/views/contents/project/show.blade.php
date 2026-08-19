@extends('layouts.default')
@section('head')
	<meta name="description" content="{{ $project['summary'] }}" />

	<meta property="og:url" content="{{ route('project.newGetShow', $project['slug']) }}" />
	<meta name="author" content="{{ $project['summary'] }}">
	<meta property="og:title" content="{{ $project['title'] }}" />
	<meta property="og:description" content="{{ $project['summary'] }}" />
	<meta property="og:type" content="article" />
	<meta property="og:image" content="{{ media($project->cover,'medium') }}" />

	<meta name="twitter:site" content="@PeduliIndonesia">
	<meta name="twitter:title" content="{{ $project['title'] }}">
	<meta name="twitter:description" content="{{ $project['summary'] }}">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="{{ media($project->cover,'medium') }}">

	<link rel="stylesheet" href="{{ asset('css/homepage1.3.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/project-list1.1.css') }}">
	<link rel="stylesheet" href="{{ asset('css/project-show.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.css') }}">

	<style>
		.fundraiser-title {
			font-size: 14px;
			color: #008797;
		}

		.fundraiser-thumb {
			width: 72px;
			height: 72px;
			object-fit: cover;
			border: 1px solid #eee;
		}

		.fundraiser-money {
			font-size: 14px;
			font-weight: bold;
			margin-top: 16px;
		}
		.select2-selection__rendered {
				line-height: 32px !important;
		}
		.select2-container .select2-selection--single {
				height: 35px !important;
		}
		.select2-selection__arrow {
				height: 34px !important;
		}
	</style>
@stop

@section('content')
	<div class="project-list-banner">
    <div class="project-detail-banner-background" >
      <div class="container-mobile">
       <div class="row">
         <div class="col-md-12 padding-top-25">
					 	<img src="{{ media($project->cover,'medium') }}" class="img-detail-banner img-responsive" style="width: 100%" alt="{{ $project['title'] }}">
						@if( $project['video'] != "" )
							<iframe width="100%" height="500" src="{{ $project['video'] }}" frameborder="0" allowfullscreen style="margin-top:32px;"></iframe>
						@endif
				 </div>
			 </div>
      </div>
    </div>
		<div class="project-detail-content-wrapper padding-top-25">
			<div class="project-detail-title">
				{{ $project['title'] }}
			</div>
			<br>
			@if ($project->parent)
			<div class="alert alert-info" role="alert">
				<?php $parentLink = route('project.newGetShow', ['slug' => $project->parent->slug]); ?>
				{!! trans('project.keterangan_sub_proyek', [
					'name' => "<strong><a href=\"{$parentLink}\">{$project->parent->title}</a></strong>"
				]) !!}
			</div>
			@endif
			@if(auth()->user())
				@if(auth()->user()->id == $project['user_id'])
					<a href="{{ route('project.getEdit', $project['slug']) }}">
						<i class="fa fa-pencil"></i>&nbsp;
						Edit
					</a>
					@if (!$project->is_fundraiser)
					<a href="{{ route('project.getWithdraw', ['slug' => $project['slug']]) }}" class="btn btn-primary" style="margin-left:32px;">Withdraw</a>
					@endif
					<br><br>
				@endif
			@endif
			@if($project['status'] == 'pending')
				<span class="label label-default">Menunggu Persetujuan Admin</span>
			@elseif($project['status'] == 'expired')
				<span class="label label-danger">Kadaluwarsa, Melewati Batas Waktu</span>
			@endif
			<div class="row" style="padding: 0 20px;">
				<div class="col-xs-6 col-md-6 padding-top-12 padding-left-0">
					<div class="detail-galang-dana-wrapper">
						<p class="small-label">Penggalang Dana Oleh</p>
						<div class="row row-margin">
							<div class="col-md-3 col-sm-3 col-3 col-xs-3">
								<img src="{{ media($project['user']['avatar'], 'small') }}" class="detail-galang-dana-profile">
							</div>
							<div class="col-md-9 col-sm-9 col-xs-9">
								<div style="display:flex;flex-direction:row;align-items:center;flex-wrap:wrap;">
									<span>{{ $project['user']['name'] }}</span>
									@if($project['user']['is_verified'])
									<div class="icon-check" style="margin-left:5px;">
										<i class="fa fa-check"></i>
									</div>
									@endif
									@if($project['user']['type_akun'] === 'Lembaga')
									<div class="icon-org" style="margin-left:5px;">
										<span>ORG</span>
									</div>
									@endif
								</div>
								@if($project['user']['is_verified'])
									<p class="small-label-grey padding-top-5">Identitas terverifikasi</p>
								@else
									<p class="small-label-grey padding-top-5">Identitas belum terverifikasi</p>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-6 padding-top-12 padding-left-0">
					<div class="detail-galang-dana-wrapper">
						<p class="small-label">Terkumpul</p>
						<div class="project-detail-title">
							{{ priceFormat($project['money_progress']) }}
						</div>
						<p class="small-label padding-top-5">dari target {{ priceFormat($project['money_target']) }}</p>
						<div class="small-progress-bar"><div class="progress-bar-active progress-bar-striped" style="width: {{ $project['progress'] }}%"></div> </div>
						<div class="detail-percentage-wrapper padding-top-5">
							<span class="small-label-bold padding-right-5">{{ $project['progress'] }}%</span><span class="small-label">Tercapai</span>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-6 padding-top-12 padding-left-0">
					<div class="detail-galang-dana-wrapper">
					@if( isExpiredDate($project['time_end']) == false )
						<p class="small-label">Tersisa</p>
							<div class="project-detail-title">
								{{ intval((strtotime($project['time_end']) - Time()) / 86400) }} hari
							</div>
					@endif
					</div>
				</div>
				@if( $project['status'] == 'active' )
				<div class="col-xs-6 col-md-6 padding-top-12 padding-left-0">
					<div class="detail-galang-dana-wrapper">
						<p class="small-label">Bagikan ke Teman</p>
						<div class="detail-share-logo-wrapper padding-top-5">
								<a type="button" style="color: #4267b2;" class="btn-share" href="https://www.facebook.com/sharer/sharer.php?u={{ Request::fullUrl() }}&quote={{ $project['title'] }}%0a{{ $project['summary'] }}%0a%0aMari infak dengan klik:" target="_blank">
									<i class="fa fa-facebook-square padding-right-10 share-icon"></i>
								</a>
								<a type="button" style="color: #30b042;" class="btn-share" href="https://api.whatsapp.com/send?text={{ $project['title'] }}%0a{{ $project['summary'] }}%0a%0aMari infak dengan klik:%0a{{ Request::fullUrl() }}" target="_blank">
									<i class="fa fa-whatsapp padding-right-10 share-icon"></i>
								</a>
								<a type="button" style="color: #1ca1f2;" class="btn-share" href="https://twitter.com/intent/tweet?text={{ $project['title'] }}%0a{{ $project['summary'] }}%0a%0aMari infak dengan klik:%0a{{ Request::fullUrl() }}" target="_blank">
									<i class="fa fa-twitter padding-right-10 share-icon"></i>
								</a>
						</div>
					</div>
				</div>
				@endif
			</div>
			@if (auth()->check() && auth()->user()->is_internal)
				<br>
				<p class="form-donasi-title">{{ trans('homepage.link_referral') }}</p>
				<a>{{ route('project.newGetShow', ['slug' => $project->slug]) . '/?r=' . auth()->user()->code_referral }}</a>
				<br><br>
				<button type="button" class="btn btn-primary btn-copy-referral">{{ trans('homepage.salin') }}</button>
			@endif
			@if( $project['status'] == 'active' )
				@if(count($project['rewards']) > 0)
				<div id="reward">
					<header class="text-center">
						<h3><strong>Silahkan Pilih</strong></h3>
					</header>
					<div class="row display-flex">
						@foreach($project['rewards']->sortBy('price') as $reward)
							<div class="col-xs-6">
								<a class="rewards reward-item" href="#" style="text-decoration: none;display: flex;flex: 1;cursor: default;">
									<div class="rewards" style="flex: 1;">
										<header>
											<h4>{{ priceFormat($reward['price']) }}</h4>
										</header>
										@if($reward['cover'])
											<img style="margin-bottom: 5px;" src="{{ media($reward['cover'], 'medium') }}" class="img-responsive" alt="{{ $reward['content'] }}">
										@endif
										<p style="margin: 0;">{{ $reward['content'] }}</p>
										<strong>
											{{ isset($selectedBySupporters[$reward->id]) ? $selectedBySupporters[$reward->id] : 0 }} orang memilih ini.
										</strong>
										<button type="button" class="btn btn-white-large reward-item-btn" style="width: 100%">Pilih</button>
										<div class="d-flex reward-item-btn-qty" style="display: none;">
											<button type="button" class="btn btn-white-large reward-item-btn-minus">-</button>
											<div style="font-size: 24px;margin-top: 10px;" class="reward-item-qty" data-id="{{ $reward['id'] }}" data-max_name_count="{{ $reward['max_name_count'] }}">1</div>
											<button type="button" class="btn btn-white-large reward-item-btn-plus">+</button>
										</div>
									</div>
								</a>
							</div>
						@endforeach
					</div>
				</div>
				@endif
			@endif
			@if($project['status'] == 'active')
			<div class="form-donasi-wrapper" @if(count($project['rewards']) > 0) style="display: none;" @endif>
				<form class="form-horizontal" id="form" method="post" onsubmit="return submitForm();">
					<div class="form-donasi-subtitle">Silahkan lengkapi data di bawah ini</div>
					<div class="space-margin"></div>
					<input type="hidden" name="code_referral" id="code-referral" value="{{ request('r') }}">
					@if(count($project['rewards']) > 0)
					<label class="label-donasi" for="nominal">Opsi Pilihan yang dipilih <a href="#" id="reward-back">Ubah data pesanan</a></label>
					@foreach($project['rewards'] as $reward)
						<div class="rewards reward-item-selected" data-id="{{ $reward['id'] }}" style="display: none;padding: 10px; margin: 0 0 5px;">
							<header>
								<h4 style="margin: 0 0 5px;" data-value="{{ $reward['price'] }}">{{ priceFormat($reward['price']) }}</h4>
							</header>
							<p style="margin: 0;">{{ $reward['content'] }}</p>
							<strong>
								{{ isset($selectedBySupporters[$reward->id]) ? $selectedBySupporters[$reward->id] : 0 }} orang memilih ini.
							</strong>
							@if ((strpos($project->title, "Zakat") !== false) or (strpos($project->title, "Qurban") !== false))
							<div style="display: flex;flex-direction: row;align-items: center;justify-content: space-between;margin-top: 10px;">
								@if ((strpos($project->title, "Zakat") !== false))
									<p style="margin: 0;margin-bottom: -5px;"><strong>Tuliskan nama yang berzakat</strong></p>
								@endif
								@if ((strpos($project->title, "Qurban") !== false))
									<p style="margin: 0;margin-bottom: -5px;"><strong>Tuliskan nama pengqurban</strong></p>
								@endif
								<button class="btn btn-sm btn-success add-name">Tambah Nama</button>
							</div>
							@endif
						</div>
					@endforeach
					<p style="font-weight:bold;font-size: 16px;" id="reward-total-amount">Total Rp 0</p>
					@else
					<label class="label-donasi" for="nominal">{{ trans('homepage.nominal_infak') }}</label>
					<input type="number" class="form-control input-nominal-donasi input-donasi-text" name="amount" id="nominal" placeholder="{{ trans('homepage.placeholder_infak') }}" min="1" required>
					@endif
					<label class="label-donasi" for="fullname">{{ trans('homepage.fullname') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="fullname" name="fullname" value="{{ auth()->user() ? auth()->user()->name : '' }}" placeholder="{{ trans('homepage.placeholder_fullname') }}" required>
					<label class="label-donasi" for="email">{{ trans('homepage.email') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="email" name="email" value="{{ auth()->user() ? auth()->user()->email : '' }}" placeholder="{{ trans('homepage.placeholder_email') }}">
					<label class="label-donasi" for="phone">{{ trans('homepage.phone') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="phone" name="phone" value="{{ auth()->user() ? auth()->user()->phone : '' }}" placeholder="{{ trans('homepage.placeholder_phone') }}" required>
					@if ($transaksi_city_input == 'true')
					<label class="label-donasi" for="city">{{ trans('homepage.city') }}</label>
					<select class="form-control input-data-donasi input-donasi-text" id="city" name="city" style="width: 100%;" required>
						<option value="">{{ trans('homepage.placeholder_city') }}</option>
						@foreach(config('web.dropdown.city') as $item)
							<option value="{{ $item }}">{{ $item }}</option>
						@endforeach
					</select>
					@endif
					<label class="label-donasi" for="notes">Doa, harapan atau diniatkan atas nama</label>
					<textarea type="text" class="form-control input-donasi-text-area input-donasi-text" id="notes" name="notes" placeholder="Ketik doa, harapan atau diniatkan atas nama disini..." rows="5" id="comment"></textarea>
					<label class="label-donasi" for="paymentMethod">{{ trans('homepage.paymentMethod') }}</label>
					<div>
					<select class="form-control input-data-donasi input-donasi-text" id="paymentMethod" name="paymentMethod" style="width: 100%;" required>
						<option value="">{{ trans('homepage.placeholder_paymentMethod') }}</option>
						@foreach($payment_group as $item)
							<optgroup label="{{ $item->name }}">
								@foreach ($item->paymentMethodProject($item->id) as $item2)
									<option value="{{ $item2->code }}" data-img="{{ asset('images/payment_methods/'.$item2->logo) }}">{{ $item2->name }}</option>
								@endforeach
							</optgroup>
						@endforeach
					</select>
					</div>
					<div class="check-donasi-wrapper">
						<input type="hidden" id="user_id" value="{{ auth()->user() ? auth()->user()->id : '' }}">
						<input type="hidden" id="project_id" value="{{ $project['id'] }}">
						<input type="checkbox" class="check-donasi" id="is_anonim" value="1">
						<label class="form-check-label label-donasi" for="is_anonim">{{ trans('homepage.hamba_allah_donasi') }}</label>
					</div>
					<div class="button-donasi-wrapper text-center">
						<img src="{{ asset('loading.gif') }}" id="loading-donasi" style="display: none;">
						<button type="submit" class="btn btn-blue-large" id="btn-donasi">{{ trans('homepage.donasi_sekarang') }}</button>
					</div>
				</form>
			</div>
			@endif
			<div class="detail-tab-wrapper">
			 <div class="col-md-12 padding-0">
				 <ul class="nav nav-tabs detail-tab-header-wrapper">
					 @if (!$project->is_fundraiser)
					 <li class="nav detail-tab-header col-md-3 col-xs-3 padding-right-0 detail-tab-header-left active"><a data-toggle="tab" href="#description" style="height:60px;">	Deskripsi</a></li>
					 <li class="nav detail-tab-header col-md-3 col-xs-3 padding-right-0 detail-tab-header-center"><a data-toggle="tab" href="#info">Info Terbaru</a></li>
					 <li class="nav detail-tab-header col-md-3 col-xs-3 padding-right-0 detail-tab-header-center"><a data-toggle="tab" href="#donatur">Donatur ({{ $acceptedSupporters->count() }})</a></li>
					 <li class="nav detail-tab-header col-md-3 col-xs-3 padding-right-0 detail-tab-header-right"><a data-toggle="tab" href="#fundraiser">Fundraiser ({{ $acceptedChildren->count() }})</a></li>
					 @else
					 <li class="nav detail-tab-header col-md-4 col-xs-4 padding-right-0 detail-tab-header-left active"><a data-toggle="tab" href="#description">	Deskripsi</a></li>
					 <li class="nav detail-tab-header col-md-4 col-xs-4 padding-right-0 detail-tab-header-center"><a data-toggle="tab" href="#info">Info Terbaru</a></li>
					 <li class="nav detail-tab-header col-md-4 col-xs-4 padding-right-0 detail-tab-header-right"><a data-toggle="tab" href="#donatur">Donatur ({{ $acceptedSupporters->count() }})</a></li>
					 @endif
				 </ul>
				 <div class="tab-content detail-tab-content-wrapper">
					 <div class="tab-pane fade in active" id="description">
							{!! $project['content'] !!}
					 </div>
					 <div class="tab-pane fade " id="info">
							@if(auth()->user())
								@if($project['user_id'] == auth()->user()->id)
									<a href="{{ route('project.getUpdate').'?project_id='.$project['id'] }}" class="btn btn-primary btn-block">Tambah Info Terbaru</a>
									<br>
								@endif
							@endif
						 @if(count($project['updates']))
							 @foreach( $project['updates'] as $upd)
							 <div class="line-info"></div>
							 <div class="info-wrapper">
								 <div class="tab-info-content-wrapper">
										<div class="info-bar margin-bottom-20">
											<div class="row">
												<div class="col-xs-10 col-sm-10 col-md-10">
													<p class="small-label-grey-14 padding-top-5">{{ formatTime($upd['created_at']) }}</p>
													<a href="{{ URL::Route('project.showUpdate', $upd['id'] ) }}" class="small-title-600">{{ $upd['title'] }}</a>
												</div>
												<div class="col-xs-2 col-sm-2 col-md-2">
													<p class="blue-title-600 padding-top-12"><i class="fa fa-chevron-down"></i></p>
												</div>
											</div>
											<div class="info-bar-content">{!! $upd['description'] !!}</div>
										</div>
								 </div>
							 </div>
							 @endforeach
						 @endif
					 </div>
					 <div class="tab-pane fade" id="donatur">
						 @if(count($project['supporters']))
							 @foreach($project['supporters'] as $sup)
								 @if($sup['status'] == "accept")
									 @if ($sup['email'] != "")
									 <div class="tab-info-content-wrapper">
										 <div class="donatur-info-bar margin-bottom-20">
											 <div class="row">
												 <div class="col-md-6">
													 <p class="small-title-600">
														@if ($sup['is_anonim'])
														Hamba Allah
														@else
													 	{{ $sup['fullname'] }}
														@endif
													 </p>
													 <p class="small-label-grey-14">{{ formatTime($sup['created_at']) }}</p>
												 </div>
												 <div class="col-md-6">
													 <p class="blue-title-600 padding-top-12">{{ priceFormat($sup['unique_code'] ? $sup['money'] + $sup['unique_code'] : $sup['money']) }}</p>
												 </div>
											 </div>
												<p style="padding: 0;margin: 0;">{{ $sup['notes'] }}</p>
										 </div>
									 </div>
									 @endif
								 @endif
							 @endforeach
						 @endif
				   </div>
					 @if (!$project->is_fundraiser)
					 <div class="tab-pane fade" id="fundraiser">
						@if($project['status'] == 'active')
							<a href="{{ route('project.getFundraiser', ['id' => $project['id']]) }}" class="btn btn-primary btn-block">Menjadi Fundraiser Galang Dana Ini</a>
							<br>
						@endif
						 @foreach($acceptedChildren as $child)
						 <div class="tab-info-content-wrapper">
						 	<div class="donatur-info-bar margin-bottom-20">
								<a href="{{ route('project.newGetShow', ['slug' => $child->slug]) }}">
									<p class="small-title-600 fundraiser-title">
										{{ $child->title }}
									</p>
								</a>
								<div style="display:flex;margin-bottom:16px;">
									<img src="{{ media($child['user']['avatar'], 'small') }}" style="width:48px;height:48px;object-fit:cover;">
									<div style="margin-left:8px;">
										<div style="display:flex;flex-direction:row;align-items:center;flex-wrap:wrap;">
											<span>{{ $child['user']['name'] }}</span>
											@if($child['user']['is_verified'])
											<div class="icon-check" style="margin-left:5px;">
												<i class="fa fa-check"></i>
											</div>
											@endif
											@if($child['user']['type_akun'] === 'Lembaga')
											<div class="icon-org" style="margin-left:5px;">
												<span>ORG</span>
											</div>
											@endif
										</div>
										@if($child['user']['is_verified'])
											<p class="small-label-grey padding-top-5">Identitas terverifikasi</p>
										@else
											<p class="small-label-grey padding-top-5">Identitas belum terverifikasi</p>
										@endif
									</div>
								</div>
								<p class="small-label-grey-14" style="font-size:12px;">
									Mengajak {{ $child->supporters->count() }} orang berdonasi
								</p>
								<p class="fundraiser-money">Rp. {{ number_format($child->money_progress) }}</p>
						 	</div>
						 </div>
						 @endforeach
						</div>
				   </div>
					 @endif
			 	</div>
			</div>
		</div>
	</div>

	<div class="modal fade" style="overflow-y:auto;" id="successPayment" tabindex="-1" role="dialog" aria-labelledby="successPaymentLabel" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background: #008797; color: white;">
					<h4 class="modal-title" id="successPaymentLabel" style="font-weight: bold;">{{ trans('homepage.success_payment') }}</h4>
				</div>
				<div class="modal-body">
					
				</div>
			</div>
		</div>
	</div>

	@if ((strpos($project->title, "Zakat") !== false) or (strpos($project->title, "Qurban") !== false))
	<div class="modal fade" id="doaDonasi" tabindex="-1" role="dialog" aria-labelledby="doaDonasiLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					@if (strpos($project->title, "Qurban"))
					<h4 class="modal-title" id="doaDonasiLabel">Niat Qurban</h4>
					@endif
					@if (strpos($project->title, "Zakat"))
					<h4 class="modal-title" id="doaDonasiLabel">Niat Zakat</h4>
					@endif
				</div>
				<div class="modal-body">
					<p>Bismillahirrahmanirrahim</p>
					@if (strpos($project->title, "Qurban") !== false)
					<p>Saya niat berkurban karena Allah ta'ala, atas nama:</p>
					@endif
					@if (strpos($project->title, "Zakat") !== false)
					<p>Saya niat berzakat karena Allah ta'ala, atas nama:</p>
					@endif
				</div>
				<button type="button" data-dismiss="modal" class="btn btn-blue-large">Lanjut Bayar</button>
			</div>
		</div>
	</div>
	@endif

	<div class="reward-next" style="display: none;">
		<div style="display: flex;align-items: center;">
			<p style="font-weight: bold;flex: 1;margin: 0;font-size: 18px;">Total Rp 0</p>
			<button type="button" class="btn btn-blue-large" style="width: auto;flex: 1;">Lanjutkan</button>
		</div>
		<input type="hidden" id="total-amount" value="" />
		<input type="hidden" id="reward-id-array" value="" />
	</div>
@stop
@section('scripts')
	<!-- Meta Pixel Code -->
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'campaign',
			content_category: "{{ $project['category']['category_name'] }}",
			content_name: "{{ $project['title'] }}",
			content_ids: ["{{ $project['slug'] }}"],
			contents: [{'id': "{{ $project['slug'] }}", 'quantity': 0}]
		});
	</script>
	<!-- End Meta Pixel Code -->
	<script src="{{ !config('services.midtrans.isProduction') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
	<script src="{{ url('assets/admin/js/select2.full.js') }}"></script>
	<script type="text/javascript">
		function openSnapPayment(data) {
			if (!data || !data.snap_token || typeof window.snap === 'undefined') {
				alert('Pembayaran Snap belum siap. Silakan coba lagi.');
				return;
			}

			window.snap.pay(data.snap_token, {
				showOrderId: false,
				language: 'id',
				onSuccess: function () { location.reload(); },
				onPending: function () { location.reload(); },
				onError: function () { location.reload(); },
				onClose: function () {}
			});
		}

			function submitForm() {
				// Kirim request ajax
				$('#loading-donasi').show();
				$('#btn-donasi').hide();
				var reward_id = $('#reward-id-array').val() !== "" ? $('#reward-id-array').val() : undefined;
				var amount = reward_id ? $('#total-amount').val() : $('input#nominal').val().replace(/\D/g,'');
				var paymentData = {
						_method: 'POST',
						_token: $('meta[name="csrf-token"]').attr('content'),
						user_id: $('input#user_id').val(),
						project_id: $('input#project_id').val(),
						money: amount,
						fullname: $('input#fullname').val(),
						phone: $('input#phone').val(),
						city: $('select#city').val(),
						email: $('input#email').val(),
						notes: $('textarea#notes').val(),
						is_anonim: $('input:checked#is_anonim').val(),
						payment_method: $('select#paymentMethod').val(),
						code_referral: $('#code-referral').val() || undefined,
						reward_id: reward_id,
				};

				function sendPayment(retry) {
					paymentData._token = $('meta[name="csrf-token"]').attr('content');
					$.ajax({
						url: "{{ url('midtrans/store/project') }}",
						type: 'POST',
						data: paymentData,
						headers: {
							'X-CSRF-TOKEN': paymentData._token
						},
						success: function (data, status) {
					fbq('track', 'Purchase', {
						content_name: 'infak_umum',
						currency: 'IDR',
						value: amount,
						content_ids: ['infak_umum'],
						contents: [{'id': 'infak_umum', 'quantity': amount}]
					});
						$('input#nominal').val('');
						$('input#fullname').val('');
						$('input#phone').val('');
						$('select#city').val('');
						$('input#email').val('');
						$('input#notes').val('');
						// var paymentMethod = $('select#paymentMethod').val();

						$('#loading-donasi').hide();
						$('#btn-donasi').show();

						if ($('#reward-id-array').val() != '') {
							var reward_idArray = JSON.parse($('#reward-id-array').val());
							reward_idArray.forEach((item) => {
								$('#doaDonasi .modal-body').append(`<p style="margin-top: 10px;">${item.desc}</p>`);
								var names = item.name.split(',');
								
								var htmlNames = `<ol>`;
								names.forEach((itemName) => {
									htmlNames += `<li>${itemName}</li>`;
								});
								htmlNames += `</ol>`;
								$('#doaDonasi .modal-body').append(htmlNames);
							});
						}

						// if (paymentMethod.includes("transfer_")) {
							// manual transfer
							if (typeof data === 'object' && data.snap_token) {
								openSnapPayment(data);
								return;
							}
							$('#successPayment .modal-body').html(data);
							$('#successPayment').modal('show');
							$('#doaDonasi').modal('show');
						// }else {
						// 	// midtrans
						// 	snap.pay(data.snap_token, {
						// 			showOrderId: false,
						// 			language: 'id',
						// 			onSuccess: function (result) {
						// 					location.reload();
						// 			},
						// 			onPending: function (result) {
						// 					location.reload();
						// 			},
						// 			onError: function (result) {
						// 					location.reload();
						// 			}
						// 	});
						// }
						},
						error: function (xhr) {
							var response = xhr.responseJSON || {};
							if (!retry && xhr.status === 419 && response.csrf_token) {
								$('meta[name="csrf-token"]').attr('content', response.csrf_token);
								paymentData._token = response.csrf_token;
								sendPayment(true);
								return;
							}

							$('#loading-donasi').hide();
							$('#btn-donasi').show();
							if (xhr.status === 419) {
								alert('Sesi halaman sudah berubah. Halaman akan dimuat ulang.');
								window.location.reload();
								return;
							}

							alert('Pembayaran gagal diproses. Silakan coba lagi.');
						}
					});
				}

				sendPayment(false);
				return false;
		}

function formatState (state) {
	var elem = state.element;
	if(elem == null) return state.text;
	var tagName = elem.tagName;
	var exclude = ['', 'other_va'];
	if(tagName != 'OPTION' || (exclude.includes(elem.value))) {
		return $(`<b>${state.text}</b>`);
	}
  var res = `
		<div class="payment-method-item">
			<div class="payment-method-item-image">
				<img src="${state.element.getAttribute('data-img')}" />
			</div>
			<div class="payment-method-item-text">
				${state.text}
			</div>
		</div>
	`;
  return $(res);
};

    $(document).ready(function(){
			$('#doaDonasi').on('hidden.bs.modal', function (e) {
				$('body').addClass("modal-open");
			});
			$('#paymentMethod').select2({
				templateResult: formatState,
				templateSelection: formatState,
			});
			$('#city').select2({
				tags: true
			});

			$("#campaign img").each(function(){
				$(this).attr("style","").addClass("img-responsive");
			});

			$("#campaign p").each(function(){$(this).attr("style","")});
			$("#campaign span").each(function(){$(this).attr("style","")});

			var infoClosed = $('.info-bar-content').slideUp();
			$(".info-bar").click(function(){
				$(this).find(infoClosed).slideToggle();
			});

			@if (auth()->check() && auth()->user()->is_internal)
			$('.btn-copy-referral').on('click', function() {
				var link = "{{ route('project.newGetShow', ['slug' => $project->slug]) . '?r=' . auth()->user()->code_referral }}";

				navigator.clipboard.writeText(link).then(function(clipText) {
					alert("{{ trans('homepage.salin_link_referral') }}");
				});
			});
			@endif
		});

		function checkPilihanSelected() {
			var countSelected = $('#reward .rewards.reward-item-selected').length;

			if (countSelected > 0) {
				$('.reward-next').fadeIn();
			}else {
				$('.reward-next').fadeOut();
			}
		}

		function numberWithCommas(x) {
    	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}
		
		function calculateTotalAmount() {
			var totalAmount = 0;
			
			$('#reward .rewards.reward-item-selected').each(function( index ) {
				var dataID = $(this).find('.reward-item-qty').attr('data-id');
				var dataQty = $(this).find('.reward-item-qty').html();
				var valuePrice = $('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"] h4').attr('data-value');

				totalAmount += (parseInt(valuePrice) * parseInt(dataQty));
			});
			$('#total-amount').val(totalAmount);
			$('#reward-total-amount').html('Total Rp ' + numberWithCommas(totalAmount));
			$('.reward-next p').html('Total Rp ' + numberWithCommas(totalAmount));
		}

		$('.reward-item').click(function(){
			return false;
		});

		$('.reward-next').click(function(){
			var countSelected = $('#reward .rewards.reward-item-selected').length;
			var reward_idArray = [];

			if (countSelected <= 0) {
				alert('Harap pilih salah satu opsi untuk melanjutkan');
			}

			$('.form-donasi-wrapper').fadeIn();
			$('.form-donasi-wrapper .reward-item-selected').hide();
			$('.form-donasi-wrapper .reward-item-selected').removeClass('active');
			$('#reward .rewards.reward-item-selected').each(function( index ) {
				var dataID = $(this).find('.reward-item-qty').attr('data-id');
				var dataMaxNameCount = $(this).find('.reward-item-qty').attr('data-max_name_count');
				var dataQty = $(this).find('.reward-item-qty').html();
				var dataPrice = $('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"] h4').html();
				var dataDesc = $('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"] p').html();
				var valuePrice = $('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"] h4').attr('data-value');

				$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"]').attr('data-qty', dataQty);
				$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"]').attr('data-max_name_count', dataMaxNameCount);
				$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"]').fadeIn();
				$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"]').addClass('active');
				$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"] h4').html('Rp ' + numberWithCommas(valuePrice) + ' x ' + dataQty);

				reward_idArray.push({
					id: dataID,
					price: dataPrice,
					desc: dataDesc,
					qty: dataQty,
					name: '',
				})

				var onlyZakatAndQurban = "{{ (strpos($project->title, 'Zakat') !== false) or (strpos($project->title, 'Qurban') !== false) }}"

				if (onlyZakatAndQurban) {
					for (var i = 0; i < dataQty; i++) {
						$('.form-donasi-wrapper .reward-item-selected[data-id="'+dataID+'"]').append(`<div style="display: flex;flex-direction: row;align-items: center;margin-top: 10px;">
										<input class="form-control input-name" style="width: 90%" placeholder="Input nama disini" />
										<button type="button" class="minus-name btn btn-danger btn-sm" style="margin-left: 10px;">-</button>
									</div>`);
					}
				}
			});

			if (reward_idArray.length > 0) {
				$('#reward-id-array').val(JSON.stringify(reward_idArray));
			}
			$('#reward').fadeOut();
			$('.reward-next').fadeOut();
			window.location.href = "#form";

			return false;
		});

		$('.form-donasi-wrapper .reward-item-selected').on('change', '.input-name', function(){
			var dataID = $(this).parents('.reward-item-selected').attr('data-id');
			var dataNames = [];
			$(this).parents('.reward-item-selected').find('.input-name').each(function(){
				dataNames.push($(this).val());
			});
			var reward_idArray = JSON.parse($('#reward-id-array').val());
			var newArray = reward_idArray.map((item) => {
				if (item.id == dataID) {
					return {
						...item,
						name: dataNames.join(','),
					}
				}

				return item;
			})
			$('#reward-id-array').val(JSON.stringify(newArray));
		});

		$('.form-donasi-wrapper').on('click', '.add-name', function(){
			var dataQty = parseInt($(this).parents('.reward-item-selected').attr('data-qty'));
			var dataMaxNameCount = parseInt($(this).parents('.reward-item-selected').attr('data-max_name_count'));
			var currentCount = parseInt($(this).parents('.reward-item-selected').find('.input-name').length);
			
			if (currentCount + 1 <= (dataMaxNameCount * dataQty)) {
				$(this).parents('.reward-item-selected').append(`<div style="display: flex;flex-direction: row;align-items: center;margin-top: 10px;">
									<input class="form-control input-name" style="width: 90%" placeholder="Input nama disini" />
									<button type="button" class="minus-name btn btn-danger btn-sm" style="margin-left: 10px;">-</button>
								</div>`);
			}else {
				alert(`Maksimal input ${currentCount} nama`);
			}
		});

		$('.form-donasi-wrapper .reward-item-selected').on('click', '.minus-name', function(){
			$(this).parent().remove();
		});

		$('.reward-item-btn').click(function(){
			$(this).hide();
			$(this).parent().find('.reward-item-btn-qty').fadeIn();
			$(this).parent().addClass('reward-item-selected');
			checkPilihanSelected();
			calculateTotalAmount();

			return false;
		});

		$('.reward-item-btn-minus').click(function(){
			var qty = $(this).parent().parent().find('.reward-item-qty').html();
			qty = parseInt(qty) - 1;
			if (qty <= 0) {
				$(this).parent().parent().find('.reward-item-btn-qty').hide();
				$(this).parent().parent().find('.reward-item-btn').fadeIn();
				$(this).parent().parent().removeClass('reward-item-selected');
				checkPilihanSelected();
			}else {
				$(this).parent().parent().find('.reward-item-qty').html(qty);
			}
			calculateTotalAmount();

			return false;
		});

		$('.reward-item-btn-plus').click(function(){
			var qty = $(this).parent().parent().find('.reward-item-qty').html();
			qty = parseInt(qty) + 1;
			$(this).parent().parent().find('.reward-item-qty').html(qty);
			calculateTotalAmount();

			return false;
		});

		$('#reward-back').click(function(){
			$('.form-donasi-wrapper').fadeOut();
			$('#reward').fadeIn();
			$('.form-donasi-wrapper .reward-item-selected').hide();
			$('.form-donasi-wrapper .reward-item-selected').removeClass('active');
			$('#reward .rewards.reward-item-selected').find('.reward-item-btn').show();
			$('#reward .rewards.reward-item-selected').find('.reward-item-btn-qty').hide();
			$('#reward .rewards.reward-item-selected').removeClass('reward-item-selected');
			$('#reward-id-array').val('');
			checkPilihanSelected();
			calculateTotalAmount();

			return false;
		});
</script>
@stop
