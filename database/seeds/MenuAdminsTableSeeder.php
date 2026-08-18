<?php

use Illuminate\Database\Seeder;
use App\Models\MenuAdmin;

class MenuAdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'title' => 'Daftar Semua User',
                'link' => 'admin.user.getIndex',
            ],
            [
                'title' => 'Detail User',
                'link' => 'admin.user.getShow',
            ],
            [
                'title' => 'Daftar User Member',
                'link' => 'admin.user.getMember',
            ],
            [
                'title' => 'Daftar User Admin',
                'link' => 'admin.user.getAdmin',
            ],
            [
                'title' => 'Tambah User Admin',
                'link' => 'admin.user.createAdmin',
            ],
            [
                'title' => 'Ubah User Admin',
                'link' => 'admin.user.editAdmin',
            ],
            [
                'title' => 'Hapus User Admin',
                'link' => 'admin.user.deleteAdmin',
            ],
            [
                'title' => 'Daftar Verified User',
                'link' => 'admin.user.getVerify',
            ],
            [
                'title' => 'Daftar User Pending',
                'link' => 'admin.user.getVerifyPending',
            ],
            [
                'title' => 'Kembalikan User Pending',
                'link' => 'admin.user.putVerifyPending',
            ],
            [
                'title' => 'Terima User Pending',
                'link' => 'admin.user.putVerifyAccept',
            ],
            [
                'title' => 'Tolak User Pending',
                'link' => 'admin.user.putVerifyReject',
            ],
            [
                'title' => 'Angkat User Admin',
                'link' => 'admin.user.putAsAdmin',
            ],
        ];

        $groupPrivileges = [
            [
                'title' => 'Daftar Semua Group Privilege',
                'link' => 'admin.group_privilege.getGroupPrivilege',
            ],
            [
                'title' => 'Tambah Group Privilege',
                'link' => 'admin.group_privilege.createGroupPrivilege',
            ],
            [
                'title' => 'Ubah Group Privilege',
                'link' => 'admin.group_privilege.editGroupPrivilege',
            ],
            [
                'title' => 'Hapus Group Privilege',
                'link' => 'admin.group_privilege.deleteGroupPrivilege',
            ],
        ];

        $blogs = [
            [
                'title' => 'Daftar Semua Berita',
                'link' => 'admin.blogs.getIndex',
            ],
            [
                'title' => 'Daftar Berita Publish',
                'link' => 'admin.blogs.getPublish',
            ],
            [
                'title' => 'Daftar Berita Draft',
                'link' => 'admin.blogs.getDraft',
            ],
            [
                'title' => 'Detail Berita',
                'link' => 'blog.getShow',
            ],
            [
                'title' => 'Tambah Berita',
                'link' => 'blog.getCreate',
            ],
            [
                'title' => 'Ubah Berita',
                'link' => 'blog.getEdit',
            ],
            [
                'title' => 'Hapus Berita',
                'link' => 'blog.destroy',
            ],
        ];

        $events = [
            [
                'title' => 'Daftar Semua Event',
                'link' => 'admin.events.getIndex',
            ],
            [
                'title' => 'Daftar Partisipan Event',
                'link' => 'admin.events.getIndexUser',
            ],
            [
                'title' => 'Detail Event',
                'link' => 'event.getShow',
            ],
            [
                'title' => 'Tambah Event',
                'link' => 'event.getCreate',
            ],
            [
                'title' => 'Ubah Event',
                'link' => 'event.getEdit',
            ],
            [
                'title' => 'Hapus Event',
                'link' => 'event.destroy',
            ],
        ];

        $notif = [
            [
                'title' => 'Daftar Notifikasi WA',
                'link' => 'admin.notif.getIndexNotifWa',
            ],
            [
                'title' => 'Edit Konten Notif Wa',
                'link' => 'admin.notif.getEditNotifWa',
            ],
            [
                'title' => 'Daftar Notifikasi Email',
                'link' => 'admin.notif.getIndexNotifEmail',
            ],
            [
                'title' => 'Edit Konten Notif Email',
                'link' => 'admin.notif.getEditNotifEmail',
            ],
        ];

        $projects = [
            [
                'title' => 'Daftar Semua Project',
                'link' => 'admin.project.getIndex',
            ],
            [
                'title' => 'Daftar Project Diterima',
                'link' => 'admin.project.getActive',
            ],
            [
                'title' => 'Daftar Project Ditolak',
                'link' => 'admin.project.getReject',
            ],
            [
                'title' => 'Daftar Project Pending',
                'link' => 'admin.project.getPending',
            ],
            [
                'title' => 'Daftar Project Fundraiser',
                'link' => 'admin.project.getFundraiser',
            ],
            [
                'title' => 'Detail Project',
                'link' => 'admin.project.getShow',
            ],
            [
                'title' => 'Ubah Project',
                'link' => 'project.getEdit',
            ],
            [
                'title' => 'Ubah Status Featured Project',
                'link' => 'admin.project.changeFeatured',
            ],
            [
                'title' => 'Terima Project',
                'link' => 'admin.project.putAccept',
            ],
            [
                'title' => 'Tolak Project',
                'link' => 'admin.project.putReject',
            ],
        ];

        $projectCategories = [
            [
                'title' => 'Daftar Semua Kategori Project',
                'link' => 'admin.page.getCategories',
            ],
            [
                'title' => 'Tambah Kategori Project',
                'link' => 'admin.page.postCategories',
            ],
            [
                'title' => 'Hapus Kategori Project',
                'link' => 'admin.page.deleteCategories',
            ],
        ];

        $banners = [
            [
                'title' => 'Daftar Semua Banner',
                'link' => 'admin.banner.getBanner',
            ],
            [
                'title' => 'Tambah Banner',
                'link' => 'admin.banner.postBanner',
            ],
            [
                'title' => 'Hapus Banner',
                'link' => 'admin.banner.deleteBanner',
            ],
            [
                'title' => 'Set Banner Popup',
                'link' => 'admin.banner.setModalPopup',
            ],
            [
                'title' => 'Hapus Banner Popup',
                'link' => 'admin.banner.removeModalPopup',
            ],
        ];

        $transactions = [
            [
                'title' => 'Daftar Transaksi Sukses',
                'link' => 'admin.transaksi.getSuccessTransaksi',
            ],
            [
                'title' => 'Daftar Transaksi Kadaluarsa',
                'link' => 'admin.transaksi.getExpiredTransaksi',
            ],
            [
                'title' => 'Daftar Transaksi Pending',
                'link' => 'admin.transaksi.getPendingTransaksi',
            ],
            [
                'title' => 'Export Excel Transaksi Sukses',
                'link' => 'admin.transaksi.getSuccessTransaksiExport',
            ],
            [
                'title' => 'Export Excel Transaksi Kadaluarsa',
                'link' => 'admin.transaksi.getExpiredTransaksiExport',
            ],
            [
                'title' => 'Export Excel Transaksi Pending',
                'link' => 'admin.transaksi.getPendingTransaksiExport',
            ],
        ];

        $projectDonations = [
            [
                'title' => 'Daftar Infak Terikat Sukses',
                'link' => 'admin.project.getSuccessSupporter',
            ],
            [
                'title' => 'Daftar Infak Terikat Kadaluarsa',
                'link' => 'admin.project.getExpiredSupporter',
            ],
            [
                'title' => 'Daftar Infak Terikat Pending',
                'link' => 'admin.project.getPendingSupporter',
            ],
            [
                'title' => 'Export Excel Infak Terikat Sukses',
                'link' => 'admin.project.getSuccessSupporterExport',
            ],
            [
                'title' => 'Export Excel Infak Terikat Kadaluarsa',
                'link' => 'admin.project.getExpiredSupporterExport',
            ],
            [
                'title' => 'Export Excel Infak Terikat Pending',
                'link' => 'admin.project.getPendingSupporterExport',
            ],
            [
                'title' => 'Kirim Catatan Infak Terikat',
                'link' => 'admin.project.submitNote',
            ],
            [
                'title' => 'Konfirmasi Cek Infak Terikat',
                'link' => 'admin.project.confirmCheck',
            ],
            [
                'title' => 'Batalkan Cek Infak Terikat',
                'link' => 'admin.project.cancelCheck',
            ],
            [
                'title' => 'Terima Infak Terikat',
                'link' => 'admin.project.putAcceptSupporter',
            ],
            [
                'title' => 'Tolak Infak Terikat',
                'link' => 'admin.project.putRejectSupporter',
            ],
        ];

        $generalDonations = [
            [
                'title' => 'Daftar Infak Umum Sukses',
                'link' => 'admin.donation.getSuccessDonation',
            ],
            [
                'title' => 'Daftar Infak Umum Kadaluarsa',
                'link' => 'admin.donation.getExpiredDonation',
            ],
            [
                'title' => 'Daftar Infak Umum Pending',
                'link' => 'admin.donation.getPendingDonation',
            ],
            [
                'title' => 'Export Excel Infak Umum Sukses',
                'link' => 'admin.donation.getSuccessDonationExport',
            ],
            [
                'title' => 'Export Excel Infak Umum Kadaluarsa',
                'link' => 'admin.donation.getExpiredDonationExport',
            ],
            [
                'title' => 'Export Excel Infak Umum Pending',
                'link' => 'admin.donation.getPendingDonationExport',
            ],
            [
                'title' => 'Kirim Catatan Infak Umum',
                'link' => 'admin.donation.submitNote',
            ],
            [
                'title' => 'Konfirmasi Cek Infak Umum',
                'link' => 'admin.donation.confirmCheck',
            ],
            [
                'title' => 'Batalkan Cek Infak Umum',
                'link' => 'admin.donation.cancelCheck',
            ],
            [
                'title' => 'Terima Infak Umum',
                'link' => 'admin.donation.putSuccessDonation',
            ],
            [
                'title' => 'Tolak Infak Umum',
                'link' => 'admin.donation.putRejectDonation',
            ],
        ];

        $zakats = [
            [
                'title' => 'Daftar Zakat Sukses',
                'link' => 'admin.zakat.getSuccessZakat',
            ],
            [
                'title' => 'Daftar Zakat Kadaluarsa',
                'link' => 'admin.zakat.getExpiredZakat',
            ],
            [
                'title' => 'Daftar Zakat Pending',
                'link' => 'admin.zakat.getPendingZakat',
            ],
            [
                'title' => 'Export Excel Zakat Sukses',
                'link' => 'admin.zakat.getSuccessZakatExport',
            ],
            [
                'title' => 'Export Excel Zakat Kadaluarsa',
                'link' => 'admin.zakat.getExpiredZakatExport',
            ],
            [
                'title' => 'Export Excel Zakat Pending',
                'link' => 'admin.zakat.getPendingZakatExport',
            ],
            [
                'title' => 'Kirim Catatan Zakat',
                'link' => 'admin.zakat.submitNote',
            ],
            [
                'title' => 'Konfirmasi Cek Zakat',
                'link' => 'admin.zakat.confirmCheck',
            ],
            [
                'title' => 'Batalkan Cek Zakat',
                'link' => 'admin.zakat.cancelCheck',
            ],
            [
                'title' => 'Terima Zakat',
                'link' => 'admin.zakat.putSuccessZakat',
            ],
            [
                'title' => 'Tolak Zakat',
                'link' => 'admin.zakat.putRejectZakat',
            ],
        ];

        $referrals = [
            [
                'title' => 'Daftar Referral Semua Transaksi',
                'link' => 'admin.referral.getAll',
            ],
            [
                'title' => 'Export Excel Referral Semua Transaksi',
                'link' => 'admin.referral.getAllExport',
            ],
            [
                'title' => 'Daftar Referral Infak Terikat',
                'link' => 'admin.referral.getProject',
            ],
            [
                'title' => 'Export Excel Referral Infak Terikat',
                'link' => 'admin.referral.getProjectExport',
            ],
            [
                'title' => 'Daftar Referral Infak Umum',
                'link' => 'admin.referral.getDonation',
            ],
            [
                'title' => 'Export Excel Referral Infak Umum',
                'link' => 'admin.referral.getDonationExport',
            ],
            [
                'title' => 'Daftar Referral Zakat',
                'link' => 'admin.referral.getZakat',
            ],
            [
                'title' => 'Export Excel Referral Zakat',
                'link' => 'admin.referral.getZakatExport',
            ],
        ];

        $withdraws = [
            [
                'title' => 'Daftar Withdraw Sukses',
                'link' => 'admin.withdraw.getSuccessWithdraw',
            ],
            [
                'title' => 'Daftar Withdraw Gagal',
                'link' => 'admin.withdraw.getFailedWithdraw',
            ],
            [
                'title' => 'Daftar Withdraw Pending',
                'link' => 'admin.withdraw.getPendingWithdraw',
            ],
            [
                'title' => 'Terima Withdraw',
                'link' => 'admin.withdraw.putSuccessWithdraw',
            ],
            [
                'title' => 'Tolak Withdraw',
                'link' => 'admin.withdraw.putRejectWithdraw',
            ],
        ];

        $crms = [
            [
                'title' => 'Daftar CRM Transaksi Sukses',
                'link' => 'admin.crm.getSuccessTransaksi',
            ],
            [
                'title' => 'Daftar CRM Transaksi Kadaluarsa',
                'link' => 'admin.crm.getExpiredTransaksi',
            ],
            [
                'title' => 'Daftar CRM Transaksi Pending',
                'link' => 'admin.crm.getPendingTransaksi',
            ],
            [
                'title' => 'Export Excel CRM Transaksi Sukses',
                'link' => 'admin.crm.getSuccessTransaksiExport',
            ],
            [
                'title' => 'Export Excel CRM Transaksi Kadaluarsa',
                'link' => 'admin.crm.getExpiredTransaksiExport',
            ],
            [
                'title' => 'Export Excel CRM Transaksi Pending',
                'link' => 'admin.crm.getPendingTransaksiExport',
            ],
            [
                'title' => 'Kirim Pesan CRM',
                'link' => 'admin.crm.sendMessage',
            ],
        ];

        $settings = [
            [
                'title' => 'Daftar Pengaturan',
                'link' => 'admin.page.getSetting',
            ],
            [
                'title' => 'Konten Halaman',
                'link' => 'admin.page.getCustomPage',
            ],
        ];

        $notifPopup = [
            [
                'title' => 'Daftar Konten Popup Doa Zakat',
                'link' => 'admin.popup.getIndexPopupDoaZakat',
            ],
            [
                'title' => 'Daftar Konten Popup Doa Zakat',
                'link' => 'admin.popup.getIndexPopupDoaZakat',
            ],
            [
                'title' => 'Edit Konten Popup Doa Zakat',
                'link' => 'admin.popup.getEditPopupDoaZakat',
            ],
            [
                'title' => 'Daftar Konten Popup Transaksi',
                'link' => 'admin.popup.getIndexPopupTransaksi',
            ],
            [
                'title' => 'Edit Konten Popup Transaksi',
                'link' => 'admin.popup.getEditPopupTransaksi',
            ],
            [
                'title' => 'Daftar Payment Method',
                'link' => 'admin.payment_method.getPaymentMethod',
            ],
            [
                'title' => 'Edit Payment Method',
                'link' => 'admin.payment_method.getEdit',
            ],
            [
                'title' => 'Create Payment Method',
                'link' => 'admin.payment_method.getCreate',
            ],
        ];

        $menuAdmins = array_merge(
            $users,
            $groupPrivileges,
            $blogs,
            $events,
            $notif,
            $projects,
            $projectCategories,
            $banners,
            $transactions,
            $projectDonations,
            $generalDonations,
            $zakats,
            $referrals,
            $withdraws,
            $crms,
            $settings,
            $notifPopup
        );

        foreach ($menuAdmins as $menuAdmin) {
            MenuAdmin::create($menuAdmin);
        }
    }
}
