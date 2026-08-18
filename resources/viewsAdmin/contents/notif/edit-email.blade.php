@extends('admin::layouts.default')
@section('head')
<script src="{{ asset('js/blog-create.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

<div class="nav-tabs-custom">

	<div class="tab-content">
		<form method="post" action="{{ url('/backend/notif/notif_email/update/'.$notif->id) }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label mb-10">Type</label>
					<input type="text" name='type' id="type" value="{{ $notif->type }}" class="form-control" readonly required>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label mb-10">Template</label>
					<textarea name='value' id="summernote2" disabled>
							@if($notif->type == 'confirm_payment' or $notif->type == 'qurban_confirm_payment' or $notif->type == 'zakat_fitrah_confirm_payment')
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Terima Kasih Sahabat <strong>[fullname]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Semoga Allah ta'ala memudahkan niat baik Anda untuk Infak/Zakat di <a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">yukdonasi.org</a></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Silahkan melanjutkan transaksi Infak/Zakat <strong>#ID [id]</strong> dengan Transfer <strong>[amount]</strong> ke</span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814"><strong>[bank_name]</strong></span><br>
									<span style="color:#131814">No. Rek. <strong>[bank_number]</strong></span><br>
									<span style="color:#131814">a.n. [bank_account]</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">1. PENTING, lakukan pembayaran TEPAT sebesar <strong>[amount]</strong> (sertakan kode unik <strong>[unique_code]</strong> pada nominal transfer), supaya Infak/Zakat terverifikasi tanpa perlu konfirmasi.</span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Anda akan mendapatkan notifikasi WA dan Email ketika Infak/Zakat terverifikasi.</span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">2. Kami menunggu transfer Infak/Zakat Anda sampai dengan <strong>[expired_at]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Bila Infak/Zakat belum kami terima hingga batas waktu tersebut, Infak/Zakat akan dibatalkan oleh sistem.</span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Infak/Zakat Anda akan diverifikasi dalam kurun waktu maksimal 1 hari kerja.</span>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">*Apabila transfer diluar jam kerja bank atau hari libur, maka verifikasi Infak/Zakat akan mengalami keterlambatan.</span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Butuh bantuan? Silahkan hubungi kami dengan klik</span><br>
									<a  href="https://api.whatsapp.com/send?phone=6285711122646" target="_blank" style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">Tanya yukdonasi.org</a>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Salam,</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814"><a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">yukdonasi.org</a> </span><br>
									</p>
								</div>
							@elseif($notif->type == "confirm_success" or $notif->type == 'qurban_confirm_success' or $notif->type == 'zakat_fitrah_confirm_success')
							  <div style="Margin-left: 20px;Margin-right: 20px;">
								<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
								  <span style="color:#131814">Terima Kasih, Sahabat <strong>[fullname]</strong></span>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
								  <span style="color:#131814">Alhamdulillah Infak/Zakat <strong>#ID [id]</strong>, melalui <a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">yukdonasi.org</a> sejumlah <strong>[amount]</strong>, melalui <strong>Transfer [bank_name]</strong>, pada tanggal [date_transfer], telah kami terima.</span>
								</p>
								<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;">
								  آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا
								</p>
								<p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">
											  Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
								  <span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span>
								  <a  href="https://yukdonasi.org" target="_blank" style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
								</p>
								<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
								  <span style="color:#131814">Salam,</span><br>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
								  <span style="color:#131814"><a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">yukdonasi.org</a> </span><br>
								</p>
							  </div>
							@elseif($notif->type == "confirm_expired" or $notif->type == 'qurban_confirm_expired' or $notif->type == 'zakat_fitrah_confirm_expired')
								<div style="Margin-left: 20px;Margin-right: 20px;">
								<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Terima Kasih, Sahabat 
										<strong>[fullname]</strong>
									</span>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Mohon Maaf Infak/Zakat 
										<strong>#ID [id]</strong>, sejumlah 
										<strong>[amount]</strong>, melalui 
										<strong>Transfer [bank_name]</strong>, pada tanggal [date_transfer], telah melewati batas waktu.
									</span>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Silakan mengulang lagi transaksi infak/zakat Anda di</span>
									<a  href="https://yukdonasi.org" target="_blank" style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">Apabila ada pertanyaan, silahkan WA admin official di</span>
									<a  href="https://api.whatsapp.com/send?phone=6285711122646" target="_blank" style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">Tanya yukdonasi.org</a>
								</p>
								<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
								<span style="color:#131814">Salam,</span>
								<br>
								</p>
								<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
									<span style="color:#131814">
										<a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">yukdonasi.org</a> 
									</span>
									<br>
									</p>
								</div>
							@elseif($notif->type == "crm_offer")
								<div style="margin-left: 20px; margin-right: 20px;">
									<p
										class="size-16"
										style="margin-top: 0; margin-bottom: 0; font-size: 18px; line-height: 26px;"
									>
										<span style="color: #131814;">
											Assalamu'alaikum, Sahabat <strong>[fullname]</strong>
										</span>
									</p>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 20px;
											font-size: 18px;
											line-height: 26px;
										"
									>
										<span style="color: #131814;">[content]</span>
									</p>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 20px;
											font-size: 18px;
											line-height: 26px;
										"
									>
										<span style="color: #131814;">
											Apabila ada pertanyaan, silahkan WA admin official di
										</span>
										<a
											href="https://api.whatsapp.com/send?phone=6285711122646"
											target="_blank"
											style="
												background: #847e3c;
												color: white;
												padding: 15px 20px;
												display: inline-block;
												border-radius: 8px;
											"
										>
											Tanya yukdonasi.org
										</a>
									</p>
									<p
										class="size-16"
										style="
											margin-top: 40px;
											margin-bottom: 20px;
											font-size: 18px;
											line-height: 26px;
										"
									>
										<span style="color: #131814;">Salam,</span><br />
									</p>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 20px;
											font-size: 18px;
											line-height: 26px;
										"
									>
										<span style="color: #131814;">
											<a href="https://yukdonasi.org" target="_blank" style="color: #847e3c;">
												yukdonasi.org
											</a>
											
										</span>
										<br />
									</p>
								</div>
							@elseif($notif->type == "welcome")
								<div style="margin-left: 20px; margin-right: 20px;">
									<h1
										class="size-34"
										style="
											margin-top: 0;
											margin-bottom: 0;
											font-style: normal;
											font-weight: normal;
											font-size: 34px;
											line-height: 43px;
											color: #2ecc9e;
											font-family: Cabin, Avenir, sans-serif;
											text-align: center;
										"
									>
										<span style="color: #575757;">Selamat Datang, [fullname]</span>
									</h1>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 0;
											font-size: 16px;
											line-height: 24px;
											text-align: center;
										"
									>
										Halo [fullname] &#10084; Terima Kasih telah manjadi bagian dari
										keluarga Yukdonasi Saat ini anda dapat memberikan dukungan terhadap proyek
										- proyek di Zakat Kita.
									</p>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 0;
											font-size: 16px;
											line-height: 24px;
											text-align: center;
										"
									>
										Langkah selanjutnya adalah Validasi keaslian akun anda menggunakan KTP agar
										anda dapat membuat proyek.
									</p>
									<p
										class="size-16"
										style="
											margin-top: 20px;
											margin-bottom: 0;
											font-size: 16px;
											line-height: 24px;
											text-align: center;
										"
									>
										Terima kasih.
									</p>
								</div>
								<br />
								<div style="margin-left: 20px; margin-right: 20px;">
									<div class="btn btn--shadow" style="text-align: center;">
										<!--[if !mso]--><a
											style="
												border-radius: 4px;
												display: inline-block;
												font-weight: bold;
												text-align: center;
												text-decoration: none !important;
												transition: opacity 0.1s ease-in;
												color: #fff;
												box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);
												background-color: #b53f3f;
												font-family: sans-serif;
												font-size: 14px;
												line-height: 24px;
												padding: 12px 35px 13px 35px;
											"
											href="[base_url]/user/[username]/validate"
											data-width="102"
											target="_blank"
											>Validasi Akun</a
										>
										<!--[endif]-->
										<!--[if mso
											]><p style="line-height: 0; margin: 0;">&nbsp;</p>
											<v:roundrect
												xmlns:v="urn:schemas-microsoft-com:vml"
												href="[base_url]/user/[username]/validate"
												style="width: 172px;"
												arcsize="9%"
												fillcolor="#B53F3F"
												stroke="f"
												><v:shadow on="t" color="#913232" offset="0,2px"></v:shadow
												><v:textbox style="mso-fit-shape-to-text: t;" inset="0px,11px,0px,10px"
													><center
														style="
															font-size: 14px;
															line-height: 24px;
															color: #ffffff;
															font-family: sans-serif;
															font-weight: bold;
															mso-line-height-rule: exactly;
															mso-text-raise: 4px;
														"
													>
														Validasi Akun
													</center></v:textbox
												></v:roundrect
											><!
										[endif]-->
									</div>
								</div>
							@elseif($notif->type == "project_info_updated")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<h3>[project_title]</h3>
									<p><strong>[title]</strong></p>
									<p>[description]</p>
								</div>
							@elseif($notif->type == "referral_donate")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Alhamdulillah, Ada transaksi sebesar <strong>[amount]</strong> melalui link referral Anda.
										<ul>
											<li>Nama Donatur: [donor_name]</li>
											<li>Dana Masuk: [amount]</li>
											<li>Jenis Transaksi: [type]</li>
											<li>Untuk Penggalangan Dana: [project_title]</li>
										</ul>
									</p>
									<p class="size-16"
										style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;">
										آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا
									</p>
									<p class="size-16"
										style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">
										Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda,
										dan memberkahi untuk Anda apa yang masih tersisa.
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat
											lainnya, silahkan kunjungi</span>
										<a href="https://yukdonasi.org" target="_blank"
											style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Salam,</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814"><a href="https://yukdonasi.org" target="_blank"
												style="color: #847e3c;">yukdonasi.org</a> </span><br>
									</p>
								</div>
							@elseif($notif->type == "supporter_donate")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Alhamdulillah, Ada dana masuk sebesar <strong>[amount]</strong>.
										<ul>
											<li>Nama Donatur: [donor_name]</li>
											<li>Untuk Penggalangan Dana: [project_title]</li>
											<li>Dana Masuk: [amount]</li>
										</ul>
									</p>
									<p class="size-16"
										style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;">
										آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا
									</p>
									<p class="size-16"
										style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">
										Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda,
										dan memberkahi untuk Anda apa yang masih tersisa.
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat
											lainnya, silahkan kunjungi</span>
										<a href="https://yukdonasi.org" target="_blank"
											style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Salam,</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814"><a href="https://yukdonasi.org" target="_blank"
												style="color: #847e3c;">yukdonasi.org</a> </span><br>
									</p>
								</div>
							@elseif($notif->type == "project_activated")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Alhamdulillah, Penggalangan Dana <strong>[title]</strong> telah diterbitkan.
										<ul>
											<li>Judul: [title]</li>
											<li>Target Dana: [target_amount]</li>
											<li>Mulai Dari: [time_start]</li>
											<li>Berakhir Pada: [time_end]</li>
											<li>Untuk Penggalangan: [parent_title]</li>
										</ul>
									</p>
									<p class="size-16"
										style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;">
										آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا
									</p>
									<p class="size-16"
										style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">
										Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda,
										dan memberkahi untuk Anda apa yang masih tersisa.
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat
											lainnya, silahkan kunjungi</span>
										<a href="https://yukdonasi.org" target="_blank"
											style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Salam,</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814"><a href="https://yukdonasi.org" target="_blank"
												style="color: #847e3c;">yukdonasi.org</a> </span><br>
									</p>
								</div>
							@elseif($notif->type == "project_withdraw")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Alhamdulillah, Dana sebesar <strong>[amount]</strong> telah dicairkan.
										<ul>
											<li>Dari Penggalangan Dana: [title]</li>
											<li>Jumlah Dana: [amount]</li>
											<li>Tujuan Transfer: [transfer_destination]</li>
											<li>Deskripsi: [description]</li>
										</ul>
									</p>
									<p class="size-16"
										style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;">
										آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا
									</p>
									<p class="size-16"
										style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">
										Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda,
										dan memberkahi untuk Anda apa yang masih tersisa.
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat
											lainnya, silahkan kunjungi</span>
										<a href="https://yukdonasi.org" target="_blank"
											style="background: #847e3c;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">yukdonasi.org</a>
									</p>
									<p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814">Salam,</span><br>
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;">
										<span style="color:#131814"><a href="https://yukdonasi.org" target="_blank"
												style="color: #847e3c;">yukdonasi.org</a> </span><br>
									</p>
								</div>
							@elseif($notif->type == "user_verify")
								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-28" style="Margin-top: 0;Margin-bottom: 20px;font-size: 28px;line-height: 36px;">
										<span style="color:#030303">
											Validasi Akun Berhasil
										</span>
									</p>
								</div>

								<div style="Margin-left: 20px;Margin-right: 20px;">
									<p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 16px;line-height: 24px;">Halo
										[fullname], Terima kasih telah mengirimkan dokumen pribadi anda, setelah kami pertimbangkan
										Identitas yang anda kirimkan kepada kami valid dan kami memutuskan untuk memvalidasi akun anda, Sekarang
										anda dapat membuat campaign anda sendiri.
									</p>
									<p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 16px;line-height: 24px;">
										Semoga kita dapat menjadi bagian dari kemajuan Bangsa Indonesia :)
									</p>
								</div>

								<div style="Margin-left: 20px;Margin-right: 20px;">
									<div class="btn btn--shadow" style="Margin-bottom: 20px;text-align: left;">
										<!--[if !mso]-->
										<a
											style="border-radius: 4px;display: inline-block;font-weight: bold;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;color: #fff;box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);background-color: #b31b1b;font-family: 'PT Sans', 'Trebuchet MS', sans-serif;font-size: 14px;line-height: 24px;padding: 12px 35px 13px 35px;"
											href="[base_url]/projects/create" data-width="188" target="_blank"
										>
											Ciptakan Project Sekarang Juga
										</a>
										<!--[endif]-->
										<!--[if mso]><p style="line-height:0;margin:0;">&nbsp;</p><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="[base_url]/projects/create" style="width:258px" arcsize="9%" fillcolor="#B31B1B" stroke="f"><v:shadow on="t" color="#8F1616" offset="0,2px"></v:shadow><v:textbox style="mso-fit-shape-to-text:t" inset="0px,11px,0px,10px"><center style="font-size:14px;line-height:24px;color:#FFFFFF;font-family:sans-serif;font-weight:bold;mso-line-height-rule:exactly;mso-text-raise:4px">Ciptakan Project Sekarang Juga</center></v:textbox></v:roundrect><![endif]-->
									</div>
								</div>
							@elseif($notif->type == "user_unverify")
								<p>Maaf [fullname], kami tidak dapat melakukan verifikasi akun anda</p>
								<p>Terima kasih.</p>
							@endif
						</textarea>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label mb-10">Content</label>
					<textarea name='value' id="summernote" required>{{ $notif->value }}</textarea>
				</div>
			</div>
			<p class="text-center">
				{!! Form::submit('Save',['class'=>'btn btn-primary btn-lg']) !!}
			</p>
		</form>
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->

@stop
@section('scripts')
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			height: 300
		});
		$('#summernote2').summernote({
			height: 300
		});
	});
</script>
@stop