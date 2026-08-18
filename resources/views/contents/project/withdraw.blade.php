@extends('layouts.default')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/homepage1.3.css') }}">
  <link rel="stylesheet" href="{{ asset('css/project-list1.1.css') }}">
	<link rel="stylesheet" href="{{ asset('css/project-show.css') }}">
@stop

@section('content')
	<div class="project-list-banner">
    <div class="project-detail-banner-background" >
      <div class="container-mobile">
       <div class="row">
         <div class="col-md-12 padding-top-25">
					 	<img src="{{ media($project->cover,'medium') }}" class="img-detail-banner img-responsive" style="width: 100%" alt="{{ $project['title'] }}">
						@if( $project['video'] != "" )
							<iframe width="100%" height="500" src="{{ $project['video'] }}" frameborder="0" allowfullscreen></iframe>
						@endif
				 </div>
			 </div>
      </div>
    </div>
		<div class="project-detail-content-wrapper padding-top-25">
			<div class="project-detail-title">
				{{ $project->title }}
			</div>
			<br>
			<div class="row" style="padding: 0 20px;">
				<div class="col-xs-6 col-md-6 padding-top-12 padding-left-0">
					<div class="detail-galang-dana-wrapper">
						<p class="small-label">Penggalang Dana Oleh</p>
						<div class="row row-margin">
							<div class="col-md-3 col-sm-3 col-3 col-xs-3">
								<img src="{{ media($project['user']['avatar'], 'small') }}" class="detail-galang-dana-profile">
							</div>
							<div class="col-md-9 col-sm-9 col-xs-9 ">
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
			</div>
			<div class="form-donasi-wrapper">
				<form class="form-horizontal" method="post" onsubmit="return submitForm();">
					<div class="form-donasi-subtitle">{{ trans('project.withdraw_penggalangan_dana') }}</div>
					<br>
					<p class="text-center">Dana Tersisa: <strong>Rp. <span id="fund">{{ number_format($project->funds) }}</span></strong></p>
					<br>
					<div class="space-margin"></div>
					<label class="label-donasi" for="amount">{{ trans('project.nominal_withdraw') }}</label>
					<input type="number" class="form-control input-nominal-donasi input-donasi-text" name="amount" id="amount" placeholder="{{ trans('project.nominal_withdraw') }}" required>
					<label class="label-donasi" for="account-bank">{{ trans('project.nama_bank') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="account-bank" name="account_bank" value="" placeholder="{{ trans('project.nama_bank') }}" required>
          <label class="label-donasi" for="account-name">{{ trans('project.nama_pemilik_rekening') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="account-name" name="account_name" value="" placeholder="{{ trans('project.nama_pemilik_rekening') }}" required>
          <label class="label-donasi" for="account-number">{{ trans('project.nomor_rekening') }}</label>
					<input type="text" class="form-control input-data-donasi input-donasi-text" id="account-number" name="account_number" value="" placeholder="{{ trans('project.nomor_rekening') }}" required>
          <label class="label-donasi" for="description">{{ trans('project.deskripsi') }}</label>
          <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="{{ trans('project.deskripsi') }}"></textarea>
          <br>
					<div class="button-donasi-wrapper text-center">
						<img src="{{ asset('loading.gif') }}" id="loading-donasi" style="display: none;">
						<button type="submit" class="btn btn-blue-large" id="btn-donasi">{{ trans('project.withdraw_sekarang') }}</button>
					</div>
				</form>
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
	<script type="text/javascript">
		function submitForm() {
				// Kirim request ajax
				$('#loading-donasi').show();
				$('#btn-donasi').hide();

				$.ajax({
					type: "POST",
					url: "{{ route('project.postWithdraw', ['slug' => $project->slug]) }}",
					data: {
						_method: 'POST',
						_token: '{{ csrf_token() }}',
						amount: $('input#amount').val().replace(/\D/g,''),
						account_bank: $('input#account-bank').val(),
						account_name: $('input#account-name').val(),
						account_number: $('input#account-number').val(),
						description: $('textarea#description').val(),
				},
					success: function (response) {
						$('#fund').text(response.data);
						$('input#amount').val('');
						$('input#account-bank').val('');
						$('input#account-name').val('');
						$('input#account-number').val('');
						$('textarea#description').val('');

						$('#loading-donasi').hide();
						$('#btn-donasi').show();

						alert("{{ trans('project.withdraw_berhasil') }}");
					},
					error: function () {
						$('#loading-donasi').hide();
						$('#btn-donasi').show();

						alert("{{ trans('project.withdraw_gagal') }}");
					}
				});

				return false;
		}

    $(document).ready(function(){
			$('#doaDonasi').on('hidden.bs.modal', function (e) {
				$('body').addClass("modal-open");
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
		});
</script>
@stop
