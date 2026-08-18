<?php

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::create([
            'key' => 'official_facebook',
            'type' => 'string',
            'value' => 'https://facebook.com/tujuanmulia.id',
        ]);

        Option::create([
            'key' => 'official_twitter',
            'type' => 'string',
            'value' => 'https://twitter.com/tujuanmulia.id',
        ]);

        Option::create([
            'key' => 'official_instagram',
            'type' => 'string',
            'value' => 'https://instagram.com/tujuanmulia.id',
        ]);

        Option::create([
            'key' => 'official_gplus',
            'type' => 'string',
            'value' => 'https://plus.google.com/tujuanmulia.id',
        ]);

        Option::create([
            'key' => 'official_address',
            'type' => 'string',
            'value' => 'Perum IKIP Gunung Anyar B-48 <br/> Surabaya, Indonesia',
        ]);

        Option::create([
            'key' => 'site_quotes',
            'type' => 'string',
            'value' => "Zakat Kita - Platform digital zakat dan kemanusiaan Indonesia",
        ]);

        Option::create([
            'key' => 'project_featured',
            'type' => 'array',
            'value' => [1, 3, 5],
        ]);

        Option::create([
            'key' => 'homepage_vertical_ads',
            'type' => 'array',
            'value' => [
                'title' => 'Ads demo satu',
                'description' => 'Deskripsi Ads demo satu',
                'link' => 'http://tujuanmulia.id.org',
                'image' => 'http://tujuanmulia.id.org/images/iklan2.jpeg',
            ],
        ]);

        Option::create([
            'key' => 'media_image_large_size',
            'type' => 'array',
            'value' => [
                'w' => 1024,
                'h' => 1024,
            ],
        ]);

        Option::create([
            'key' => 'media_image_medium_size',
            'type' => 'array',
            'value' => [
                'w' => 500,
                'h' => 500,
            ],
        ]);

        Option::create([
            'key' => 'media_image_small_size',
            'type' => 'array',
            'value' => [
                'w' => 150,
                'h' => 150,
            ],
        ]);


        Option::create([
            'key' => 'notif_wa',
            'type' => 'confirm_payment',
            'value' => "Terimakasih, Sahabat [fullname]\n\nSemoga Allah ta'ala memudahkan niat baik Anda untuk bersedekah dan berzakat di https://tujuanmulia.id.org\n\nSilahkan melanjutkan transaksi donasi *#ID [id]* dengan transfer :\n\nNominal : *[amount]* \nBank : *[bank_name]* \nNo. Rekening : *[bank_number]* \nAtas nama : *[bank_account]*\n\nPENTING!\n\n1. Lakukan pembayaran TEPAT sebesar *[amount]* (sertakan kode unik *[unique_code]* supaya infak/zakat Anda mudah kami verifikasi.\n\n2. Kami menunggu transfer infak/zakat Anda sampai dengan [expired_at]\n\nbutuh bantuan? silakan chatting WA dengan admin kami https://api.whatsapp.com/send?phone=6285711122646\n\nSalam, \nhttps://tujuanmulia.id.org | *Laznas Nurul Hayat*",
        ]);

        Option::create([
            'key' => 'notif_wa',
            'type' => 'confirm_success',
            'value' => "*KONFIRMASI INFAK/ZAKAT BERHASIL*\n\nTerimakasih, Sahabat [fullname]\n\nAlhamdulillah, Infak/Zakat *#ID [id]* melalui https://tujuanmulia.id.org sebesar [amount] sudah kami terima\n\n آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا\n\nSemoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.\n\nTerimakasih atas kepercayaannya. Untuk informasi program infak/zakat lainnya, silahkan kunjungi https://tujuanmulia.id.org atau WA official : https://api.whatsapp.com/send?phone=6285711122646\n\nSalam,\nhttps://tujuanmulia.id.org | *Laznas Nurul Hayat*",
        ]);

        Option::create([
            'key' => 'notif_wa',
            'type' => 'confirm_expired',
            'value' => "*KONFIRMASI INFAK/ZAKAT TELAH LEWAT BATAS WAKTU*\n\nTerimakasih, Sahabat [fullname]\nMohon Maaf Infak/Zakat *#ID [id]* sejumlah *[amount]* melalui *Transfer [bank_name]*, pada tanggal *[date_transfer]*, telah melewati batas waktu.\nSilakan mengulang lagi transaksi infak/zakat Anda di https://tujuanmulia.id.org \n\nApabila ada pertanyaan, silahkan WA admin official di https://api.whatsapp.com/send?phone=6285711122646\n\nSalam,\nhttps://tujuanmulia.id.org | *Laznas Nurul Hayat*",
        ]);

        Option::create([
            'key' => 'notif_wa',
            'type' => 'crm_offer',
            'value' => "*PENAWARAN DARI ZAKAT KITA*\n\nAssalamu'alaikum, Sahabat [fullname]\n[content]\n\nApabila ada pertanyaan, silahkan WA admin official di https://api.whatsapp.com/send?phone=6285711122646\n\nSalam,\nhttps://tujuanmulia.id.org | *Laznas Nurul Hayat*",
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'confirm_payment',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"><p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"><span style="color:#131814">Terima Kasih Sahabat <strong>[fullname]</strong></span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Semoga Allah memudahkan niat baik Anda untuk Infak/Zakat di <a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a></span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Silahkan melanjutkan transaksi Infak/Zakat <strong>#ID [id]</strong> dengan Transfer <strong>[amount]</strong> ke</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814"><strong>[bank_name]</strong></span><br><span style="color:#131814">No. Rek. <strong>[bank_number]</strong></span><br><span style="color:#131814">a.n. [bank_account]</span><br></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">1. PENTING, lakukan pembayaran TEPAT sebesar <strong>[amount]</strong> (sertakan kode unik <strong>[unique_code]</strong> pada nominal transfer), supaya Infak/Zakat terverifikasi tanpa perlu konfirmasi.</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Anda akan mendapatkan notifikasi WA dan Email ketika Infak/Zakat terverifikasi.</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">2. Kami menunggu transfer Infak/Zakat Anda sampai dengan <strong>[expired_at]</strong></span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Bila Infak/Zakat belum kami terima hingga batas waktu tersebut, Infak/Zakat akan dibatalkan oleh sistem.</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Infak/Zakat Anda akan diverifikasi dalam kurun waktu maksimal 1 hari kerja.</span></p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">*Apabila transfer diluar jam kerja bank atau hari libur, maka verifikasi Infak/Zakat akan mengalami keterlambatan.</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Butuh bantuan? Silahkan hubungi kami dengan klik</span><br><a  href="https://api.whatsapp.com/send?phone=6285711122646" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">Tanya tujuanmulia.id.org</a> </p>  <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Salam,</span><br></p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br></p> </div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'confirm_success',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"><p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"><span style="color:#131814">Terima Kasih, Sahabat <strong>[fullname]</strong></span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Alhamdulillah Infak/Zakat <strong>#ID [id]</strong>, melalui <a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> sejumlah <strong>[amount]</strong>, melalui <strong>Transfer [bank_name]</strong>, pada tanggal [date_transfer], telah kami terima.</span></p><p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;"> آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا </p><p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;">Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.</p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span><a  href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a></p><p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Salam,</span><br></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br></p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'confirm_expired',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"><p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"><span style="color:#131814">Terima Kasih, Sahabat <strong>[fullname]</strong></span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Mohon Maaf Infak/Zakat <strong>#ID [id]</strong>, sejumlah <strong>[amount]</strong>, melalui <strong>Transfer [bank_name]</strong>, pada tanggal [date_transfer], telah melewati batas waktu.</span></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Silakan mengulang lagi transaksi infak/zakat Anda di</span><a  href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Apabila ada pertanyaan, silahkan WA admin official di</span><a  href="https://api.whatsapp.com/send?phone=6285711122646" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">Tanya tujuanmulia.id.org</a></p><p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814">Salam,</span><br></p><p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"><span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br></p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'crm_offer',
            'value' => '<div style="margin-left: 20px; margin-right: 20px;"><p class="size-16" style="margin-top: 0; margin-bottom: 0; font-size: 18px; line-height: 26px;"><span style="color: #131814;">Assalamu\'alaikum, Sahabat <strong>[fullname]</strong></span></p><p class="size-16" style=" margin-top: 20px; margin-bottom: 20px; font-size: 18px; line-height: 26px;"><span style="color: #131814;">[content]</span></p><p class="size-16" style="margin-top: 20px; margin-bottom: 20px; font-size: 18px; line-height: 26px;"><span style="color: #131814;">Apabila ada pertanyaan, silahkan WA admin official di</span><a href="https://api.whatsapp.com/send?phone=6285711122646" target="_blank" style=" background: #008797; color: white; padding: 15px 20px; display: inline-block; border-radius: 8px;">Tanya tujuanmulia.id.org</a></p><p class="size-16" style=" margin-top: 40px; margin-bottom: 20px; font-size: 18px; line-height: 26px;"><span style="color: #131814;">Salam,</span><br /></p><p class="size-16" style=" margin-top: 20px; margin-bottom: 20px; font-size: 18px; line-height: 26px;"><span style="color: #131814;"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br /></p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'welcome',
            'value' => '<div style="margin-left: 20px; margin-right: 20px;"> <h1 class="size-34" style=" margin-top: 0; margin-bottom: 0; font-style: normal; font-weight: normal; font-size: 34px; line-height: 43px; color: #2ecc9e; font-family: Cabin, Avenir, sans-serif; text-align: center; " > <span style="color: #575757;">Selamat Datang, [fullname]</span> </h1> <p class="size-16" style=" margin-top: 20px; margin-bottom: 0; font-size: 16px; line-height: 24px; text-align: center; " > Halo [fullname] &#10084; Terima Kasih telah manjadi bagian dari keluarga Yukdonasi Saat ini anda dapat memberikan dukungan terhadap proyek - proyek di Zakat Kita. </p> <p class="size-16" style=" margin-top: 20px; margin-bottom: 0; font-size: 16px; line-height: 24px; text-align: center; " > Langkah selanjutnya adalah Validasi keaslian akun anda menggunakan KTP agar anda dapat membuat proyek. </p> <p class="size-16" style=" margin-top: 20px; margin-bottom: 0; font-size: 16px; line-height: 24px; text-align: center; " > Terima kasih. </p></div><br /><div style="margin-left: 20px; margin-right: 20px;"> <div class="btn btn--shadow" style="text-align: center;"> <!--[if !mso]--><a style=" border-radius: 4px; display: inline-block; font-weight: bold; text-align: center; text-decoration: none !important; transition: opacity 0.1s ease-in; color: #fff; box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2); background-color: #b53f3f; font-family: sans-serif; font-size: 14px; line-height: 24px; padding: 12px 35px 13px 35px; " href="[base_url]/user/[username]/validate" data-width="102" target="_blank" >Validasi Akun</a > <!--[endif]--> <!--[if mso ]><p style="line-height: 0; margin: 0;">&nbsp;</p> <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="[base_url]/user/[username]/validate" style="width: 172px;" arcsize="9%" fillcolor="#B53F3F" stroke="f" ><v:shadow on="t" color="#913232" offset="0,2px"></v:shadow ><v:textbox style="mso-fit-shape-to-text: t;" inset="0px,11px,0px,10px" ><center style=" font-size: 14px; line-height: 24px; color: #ffffff; font-family: sans-serif; font-weight: bold; mso-line-height-rule: exactly; mso-text-raise: 4px; " > Validasi Akun </center></v:textbox ></v:roundrect ><! [endif]--> </div></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'project_info_updated',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <h3>[project_title]</h3> <p><strong>[title]</strong></p> <p>[description]</p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'referral_donate',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Alhamdulillah, Ada transaksi sebesar <strong>[amount]</strong> melalui link referral Anda. <ul> <li>Nama Donatur: [donor_name]</li> <li>Dana Masuk: [amount]</li> <li>Jenis Transaksi: [type]</li> <li>Untuk Penggalangan Dana: [project_title]</li> </ul> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;"> آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا </p> <p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;"> Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa. </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span> <a href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Salam,</span><br> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br> </p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'supporter_donate',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Alhamdulillah, Ada dana masuk sebesar <strong>[amount]</strong>. <ul> <li>Nama Donatur: [donor_name]</li> <li>Untuk Penggalangan Dana: [project_title]</li> <li>Dana Masuk: [amount]</li> </ul> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;"> آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا </p> <p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;"> Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa. </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span> <a href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Salam,</span><br> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br> </p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'project_activated',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Alhamdulillah, Penggalangan Dana <strong>[title]</strong> telah diterbitkan. <ul> <li>Judul: [title]</li> <li>Target Dana: [target_amount]</li> <li>Mulai Dari: [time_start]</li> <li>Berakhir Pada: [time_end]</li> <li>Untuk Penggalangan: [parent_title]</li> </ul> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;"> آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا </p> <p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;"> Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa. </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span> <a href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Salam,</span><br> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br> </p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'project_withdraw',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Hai, Sahabat <strong>[fullname]</strong></span> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Alhamdulillah, Dana sebesar <strong>[amount]</strong> telah dicairkan. <ul> <li>Dari Penggalangan Dana: [title]</li> <li>Jumlah Dana: [amount]</li> <li>Tujuan Transfer: [transfer_destination]</li> <li>Deskripsi: [description]</li> </ul> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;text-align: right;color: #333;"> آجَرَكَ اللهُ فِيْمَا اَعْطَيْتَ، وَبَارَكَ فِيْمَا اَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُوْرًا </p> <p class="size-16" style="Margin-top: 10px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;color: #333;font-style: italic;"> Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa. </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Terima kasih atas kepercayaannya. Untuk informasi program Infak/Zakat lainnya, silahkan kunjungi</span> <a href="https://tujuanmulia.id.org" target="_blank" style="background: #008797;color: white;padding: 15px 20px;display: inline-block;border-radius: 8px;">tujuanmulia.id.org</a> </p> <p class="size-16" style="Margin-top: 40px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814">Salam,</span><br> </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 18px;line-height: 26px;"> <span style="color:#131814"><a href="https://tujuanmulia.id.org" target="_blank" style="color: #008797;">tujuanmulia.id.org</a> | Laznas Nurul Hayat</span><br> </p></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'user_verify',
            'value' => '<div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-28" style="Margin-top: 0;Margin-bottom: 20px;font-size: 28px;line-height: 36px;"> <span style="color:#030303"> Validasi Akun Berhasil </span> </p></div><div style="Margin-left: 20px;Margin-right: 20px;"> <p class="size-16" style="Margin-top: 0;Margin-bottom: 0;font-size: 16px;line-height: 24px;">Halo [fullname], Terima kasih telah mengirimkan dokumen pribadi anda, setelah kami pertimbangkan Identitas yang anda kirimkan kepada kami valid dan kami memutuskan untuk memvalidasi akun anda, Sekarang anda dapat membuat campaign anda sendiri. </p> <p class="size-16" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 16px;line-height: 24px;"> Semoga kita dapat menjadi bagian dari kemajuan Bangsa Indonesia :) </p></div><div style="Margin-left: 20px;Margin-right: 20px;"> <div class="btn btn--shadow" style="Margin-bottom: 20px;text-align: left;"> <!--[if !mso]--> <a style="border-radius: 4px;display: inline-block;font-weight: bold;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;color: #fff;box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);background-color: #b31b1b;font-family: \'PT Sans\', \'Trebuchet MS\', sans-serif;font-size: 14px;line-height: 24px;padding: 12px 35px 13px 35px;" href="[base_url]/projects/create" data-width="188" target="_blank" > Ciptakan Project Sekarang Juga </a> <!--[endif]--> <!--[if mso]><p style="line-height:0;margin:0;">&nbsp;</p><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="[base_url]/projects/create" style="width:258px" arcsize="9%" fillcolor="#B31B1B" stroke="f"><v:shadow on="t" color="#8F1616" offset="0,2px"></v:shadow><v:textbox style="mso-fit-shape-to-text:t" inset="0px,11px,0px,10px"><center style="font-size:14px;line-height:24px;color:#FFFFFF;font-family:sans-serif;font-weight:bold;mso-line-height-rule:exactly;mso-text-raise:4px">Ciptakan Project Sekarang Juga</center></v:textbox></v:roundrect><![endif]--> </div></div>',
        ]);

        Option::create([
            'key' => 'notif_email',
            'type' => 'user_unverify',
            'value' => '<p>Maaf [fullname], kami tidak dapat melakukan verifikasi akun anda</p><p>Terima kasih.</p>',
        ]);

        Option::create([
            'key' => 'doa_zakat',
            'type' => 'niat_zakat',
            'value' => '<p class="text-right">نَوَيْتُ أَنْ أُخْرِجَ زَكاَةَ اْللَالِ عَنْ نَفْسِيْ فَرْضًالِلهِ تَعَالَى</p><p>Nawaitu an ukhrija zakata maali fardha llillahi ta\'aala.</p><p>Saya Niat Mengeluarkan Zakat Maal Dari Diriku Sendiri Fardhu Karena Allah Ta\'ala</p>',
        ]);

        Option::create([
            'key' => 'transaksi',
            'type' => 'transaksi_transfer_success',
            'value' => '<h4 class="text-center" style="font-weight: normal; font-size: 16px; margin-top: 10px;">Transfer sesuai nominal dibawah ini:</h4><div class=""><div class="row text-center"><div class="col-md-6 col-md-offset-3" style="font-size: 25px; font-weight: bold;">[nominal_kodeunik_html]</div><div class="col-md-2" style="padding: 10px; font-weight: bold;"><a id="nominal-salin" href="#">SALIN</a></div></div><br /> [input_copy_nominal_kodeunik]<div class="alert alert-warning"><table><tbody><tr><td valign="top">&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td><strong>PENTING!</strong> Mohon transfer tepat sampai 3 angka terakhir agar transaksi terverifikasi otomatis</td></tr></tbody></table></div><ul class="list-group"><li class="list-group-item"><span class="pull-right">[nominal_format]</span> Jumlah Transaksi</li><li class="list-group-item"><span class="pull-right">[kodeunik]</span> Kode Unik (*)</li></ul><p>* 3 angka terakhir akan dimasukkan transaksi.</p><br /><h4 class="text-center" style="font-weight: normal; font-size: 16px; margin-top: 10px;">Pembayaran dilakukan ke rekening a/n</h4><h4 class="text-center" style="font-weight: normal; font-size: 16px; margin-top: 10px;"><strong> [bank_account_name] </strong></h4><div class="panel panel-default"><div class="panel-body"><div class="row"><div class="col-xs-3 col-sm-3 col-md-3">[bank_name]</div><div class="col-xs-6 col-sm-6 col-md-6 text-center"><strong> [bank_account_number] </strong> [input_copy_bank_account_number]</div><div class="col-xs-2 col-sm-2 col-md-2"><a id="nomor-rekening" href="#"> <strong>SALIN</strong> </a></div></div></div></div><div class="panel panel-default"><div class="panel-body"><p>Transfer transaksi sebelum <strong>[date_expired] WIB</strong> atau zakat Anda otomatis dibatalkan oleh sistem.</p></div></div><ul class="list-group"><li class="list-group-item"><span class="pull-right">[type_transaction]</span> <strong>Jenis Transaksi</strong></li><li class="list-group-item"><span class="pull-right"> [user_name] </span> <strong>Nama</strong></li><li class="list-group-item"><span class="pull-right">[user_phone]</span> <strong>No. Whatsapp</strong></li><li class="list-group-item"><span class="pull-right">[user_email]</span> <strong>Email</strong></li></ul><br /><div style="display: flex;"><a class="btn btn-share" style="background: #30b042; color: white; margin-right: 10px; align-items: center; display: flex;" href="[share_url]" target="_blank" type="button"> Bagikan </a> <button class="btn btn-blue-large" type="button" data-dismiss="modal">Kembali</button></div></div>',
        ]);

        Option::create([
            'key' => 'transaksi',
            'type' => 'transaksi_wallet_va_success',
            'value' => '<h4 class="text-center" style="font-weight: normal; font-size: 16px; margin-top: 10px;">Transfer sesuai nominal dibawah ini:</h4><div class=""><div class="row text-center"><div class="col-md-6 col-md-offset-3" style="font-size: 25px; font-weight: bold;">[nominal_kodeunik_html]</div><div class="col-md-2" style="padding: 10px; font-weight: bold;"><a id="nominal-salin" href="#">SALIN</a></div></div><br />[input_copy_nominal_kodeunik]<div class="alert alert-warning"><table><tbody><tr><td valign="top">&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td><strong>PENTING!</strong> Mohon transfer melalui [text_info_payment] agar infak/zakat terverifikasi otomatis</td></tr></tbody></table></div><ul class="list-group"><li class="list-group-item"><span class="pull-right">[nominal_format]</span> Jumlah Infak/Zakat</li></ul><br /><h4 class="text-center" style="font-weight: normal; font-size: 16px; margin-top: 10px;">Pembayaran dilakukan [text_info_tujuan]</h4><div class="panel panel-default">[action_payment]</div><div class="panel panel-default"><div class="panel-body"><p>Transfer infak/zakat sebelum <strong>[date_expired] WIB</strong> atau infak/zakat Anda otomatis dibatalkan oleh sistem.</p></div></div><ul class="list-group"><li class="list-group-item"><span class="pull-right">[type_transaction]</span> <strong>Jenis Transaksi</strong></li><li class="list-group-item"><span class="pull-right"> [user_name] </span> <strong>Nama</strong></li><li class="list-group-item"><span class="pull-right">[user_phone]</span> <strong>No. Whatsapp</strong></li><li class="list-group-item"><span class="pull-right">[user_email]</span> <strong>Email</strong></li></ul><br /><div style="display: flex;"><a class="btn btn-share" style="background: #30b042; color: white; margin-right: 10px; align-items: center; display: flex;" href="[share_url]" target="_blank" type="button"> Bagikan </a> <button class="btn btn-blue-large" type="button" data-dismiss="modal">Kembali</button></div></div>',
        ]);

        Option::create([
            'key' => 'syarat_ketentuan',
            'type' => 'page',
            'value' => '<div></div>',
        ]);
        Option::create([
            'key' => 'bantuan',
            'type' => 'page',
            'value' => '<div></div>',
        ]);
        Option::create([
            'key' => 'tentang',
            'type' => 'page',
            'value' => '<div></div>',
        ]);
        Option::create([
            'key' => 'transaksi_city_input',
            'type' => 'string',
            'value' => 'true',
        ]);

        Option::create([
            'key' => 'client_id',
            'type' => 'string',
            'value' => '961b77bf-b5ec-43ea-b98d-b9493323a581',
        ]);
        Option::create([
            'key' => 'client_secret',
            'type' => 'string',
            'value' => 'cUaCzwSLSfjNGRerpWCSHLtNiN8fyQoVqALjtteA',
        ]);
        Option::create([
            'key' => 'api_key',
            'type' => 'string',
            'value' => 'ba9d764f-f52b-4e24-b92f-50ca64dbe003',
        ]);
        Option::create([
            'key' => 'api_secret',
            'type' => 'string',
            'value' => '6880daef-d2e4-49d0-9382-2787cb06c835',
        ]);
        Option::create([
            'key' => 'company_code',
            'type' => 'string',
            'value' => '14182',
        ]);
    }
}
