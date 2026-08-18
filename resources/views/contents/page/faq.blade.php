@extends('layouts.default')
@section('title','FAQ')
@section('content')
	<div class="header-title">
		<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
					<div class="container">
							<div class="header-title-content text-left">
									<br>
									<br>
									<h1 class="content-title">FAQ</h1>
									<!-- <p class="content-subtitle">Mari membantu dengan sesama, dengan ikhlas dan tanpa pamrih</p> -->
							</div>
					</div>
			</div>
	</div>
	
	<div class="container" style="padding: 80px 10px 100px 10px;font-size: 18px">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<section>
					<ul class="list-group">
						<li class="list-group-item" data-toggle="collapse" data-target="#1">
							<h4>Apa itu Zakat Kita ?</h4>
						</li>
						<div class="well collapse" id="1">
							<p>
								Zakat Kita adalah platform Crowdfunding pertama di Indonesia yang berkonsntrasi untuk menggalang dana untuk project musik
							</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#2">
							<h4>Fitur apa saja yang ada di Zakat Kita ?</h4>
						</li>
						<div class="well collapse" id="2">
							<p>
								Fitur yang ada di Zakat Kita adalah Homepage, Lihat Project, Buat Project Baru, Gabung dan Figure dukungan project yang ada.
							</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#3">
							<h4>Apa itu crowdfunding ?</h4>
						</li>
						<div class="well collapse" id="3">
							<p>
								Crowdfunding adalah penggalangan dana dari masyarakat untuk tujuan tertentu. Dalam hal ini Zakat Kita sebagai media penggalangan dana untuk sosial dan kreatif.
							</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#4">
							<h4>Bagaimana cara join ke Zakat Kita ?</h4>
						</li>
						<div class="well collapse" id="4">
							<p>
								Kamu tinggal masuk ke link <a href="http://yukdonasi.org/auth/register">http://yukdonasi.org/auth/register</a> dan mengisi semua form pendaftaran.
							</p>

							<p>Setelah itu kamu harus masuk ke setting dan memvalidasi identitas anda dengan mengirim foto KTP.</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#5">
							<h4>Project apa saja yang dapat di funding ?</h4>
						</li>
						<div class="well collapse" id="5">
							<p>
								Project sosial, agama, musik, teknologi, seni, makanan, design grafis dan lain-lain sesuai dengan persetujuan / verifikasi pihak pengelola web.
							</p>
						</div>

		<!-- 				<li class="list-group-item" data-toggle="collapse" data-target="#6">
							<h4>Apakah semua genre musik dapat masuk Zakat Kita ?</h4>
						</li>
						<div class="well collapse" id="6">
							<p>
								Apapun genre musik kamu selama asli original karya dari kamu sendiri, tak ada alasan bagi kami untuk tidak mendukung. Karena misi utama kami adalah membantu semua musisi Indonesia yang ingin berkembang
							</p>
						</div> -->

						<li class="list-group-item" data-toggle="collapse" data-target="#7">
							<h4>Bagaimana cara mengecek status dukungan?</h4>
						</li>
						<div class="well collapse" id="7">
							<p>
								Status dukungan akan terlihat pada profil kamu, aka nada tanda verified pada menu supporting
							</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#8">
							<h4>Apa itu rewards ?</h4>
						</li>
						<div class="well collapse" id="8">
							<p>
								semacam hadiah atau apresiasi yang kamu berikan kepada supporter yang ikut mendanai project kamu. Besaran reward berdasarkan nilai yang mereka sumbang. Silahkan kamu yang tentukan.
							</p>
						</div>

						<li class="list-group-item" data-toggle="collapse" data-target="#9">
							<h4>Jika saya ada pertanyaan lagi saya bisa menghubungi kemana ya ?</h4>
						</li>
						<div class="well collapse" id="9">
							<p>
								Kirim email ke <a mailto="crew@yukdonasi.org">crew@yukdonasi.org</a>
							</p>
						</div>
					</ul>
				</section>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
@stop
