<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="@if(segment(2) == '') active @endif">
				<a href="{{ route('admin.page.getIndex') }}">
					<i class="fa fa-dashboard"></i> Dashboard
				</a>
			</li>
			
			@if (isPermitted([
				'admin.user.getIndex',
				'admin.user.getVerify',
				'admin.user.getVerifyPending',
				'admin.user.getMember',
				'admin.user.getAdmin',
				'admin.group_privilege.getGroupPrivilege',
				'admin.blogs.getIndex',
				'admin.blogs.getPublish',
				'admin.blogs.getDraft',
				'admin.project.getIndex',
				'admin.project.getActive',
				'admin.project.getReject',
				'admin.project.getPending',
				'admin.page.getCategories',
				'admin.banner.getBanner',
				'admin.payment_method.getPaymentMethod',
			]))
			<li class="header">Main Menu</li>

			@if (isPermitted([
				'admin.user.getIndex',
				'admin.user.getVerify',
				'admin.user.getVerifyPending',
				'admin.user.getMember',
				'admin.user.getAdmin',
			]))
			<li class="treeview @if(segment(2) == 'user') active @endif">
				<a href="#">
					<i class='fa fa-user'></i> <span>User</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.user.getIndex'))
					<li @if(segment('2') == 'user' and segment('3') == '') class="active" @endif >
						<a href="{{ route('admin.user.getIndex') }}">
							<span>Semua</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.user.getVerify'))
					<li @if(segment('2') == 'user' and segment('3') == 'verified-member') class="active" @endif >
						<a href="{{ route('admin.user.getVerify') }}">
							<span>Verified Member</span>
							<span class="badge">{{ $total_verify }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.user.getVerifyPending'))
					<li @if(segment('2') == 'user' and segment('3') == 'pending-verify') class="active" @endif >
						<a href="{{ route('admin.user.getVerifyPending') }}">
							<span>Pending Member Validation</span>
							<span class="badge">{{ $total_pending_verified }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.user.getMember'))
					<li @if(segment('2') == 'user' and segment('3') == 'member') class="active" @endif >
						<a href="{{ route('admin.user.getMember') }}">
							<span>Member</span>
							<span class="badge">{{ $total_member }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.user.getAdmin'))
					<li @if(segment('2') == 'user' and segment('3') == 'admin') class="active" @endif >
						<a href="{{ route('admin.user.getAdmin') }}">
							<span>Admin</span>
							<span class="badge">{{ $total_admin }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted('admin.group_privilege.getGroupPrivilege'))
			<li class="@if(segment(2) == 'group_privilege') active @endif">
				<a href="{{ route('admin.group_privilege.getGroupPrivilege') }}">
					<i class="fa fa-tags"></i> Group Privilege
				</a>
			</li>
			@endif

			@if (isPermitted([
				'admin.notif.getIndexNotifWa',
				'admin.notif.getIndexNotifEmail',
				'admin.popup.getIndexPopupDoaZakat',
			]))
			<li class="treeview @if(segment(2) == 'notif') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Konten Notifikasi</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.notif.getIndexNotifWa'))
					<li @if(segment('2') == 'notif' and segment('3') == 'notif_wa') class="active" @endif >
						<a href="{{ route('admin.notif.getIndexNotifWa') }}">
							<span>WhatsApp</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.notif.getIndexNotifEmail'))
					<li @if(segment('2') == 'notif' and segment('3') == 'notif_email') class="active" @endif >
						<a href="{{ route('admin.notif.getIndexNotifEmail') }}">
							<span>Email</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.popup.getIndexPopupDoaZakat'))
					<li @if(segment('2') == 'popup' and segment('3') == 'doa_zakat') class="active" @endif >
						<a href="{{ route('admin.popup.getIndexPopupDoaZakat') }}">
							<span>Popup Doa Zakat</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.popup.getIndexPopupTransaksi'))
					<li @if(segment('2') == 'popup' and segment('3') == 'transaksi') class="active" @endif >
						<a href="{{ route('admin.popup.getIndexPopupTransaksi') }}">
							<span>Popup Transaksi</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.page.getCustomPage',
			]))
			<li class="treeview @if(segment(3) == 'getCustomPage') active @endif">
				<a href="{{ route('admin.page.getCustomPage') }}">
					<i class='fa fa-file-text'></i> <span>Konten Halaman</span>
				</a>
			</li>
			@endif

			@if (isPermitted([
				'admin.blogs.getIndex',
				'admin.blogs.getPublish',
				'admin.blogs.getDraft',
			]))
			<li class="treeview @if(segment(2) == 'blogs') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Berita</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.blogs.getIndex'))
					<li @if(segment('2') == 'blogs' and segment('3') == '') class="active" @endif >
						<a href="{{ route('admin.blogs.getIndex') }}">
							<span>Semua</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.blogs.getPublish'))
					<li @if(segment('2') == 'blogs' and segment('3') == 'publish') class="active" @endif >
						<a href="{{ route('admin.blogs.getPublish') }}">
							<span>Publish</span>
							<span class="badge">{{ $total_blog_publish }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.blogs.getDraft'))
					<li @if(segment('2') == 'blogs' and segment('3') == 'draft') class="active" @endif >
						<a href="{{ route('admin.blogs.getDraft') }}">
							<span>Draft</span>
							<span class="badge">{{ $total_blog_draft }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.project.getIndex',
				'admin.project.getActive',
				'admin.project.getReject',
				'admin.project.getPending',
				'admin.project.getFundraiser',
			]))
			<li class="treeview @if(segment(2) == 'project') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Project / Campaign</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.project.getIndex'))
					<li @if(segment('2') == 'project' and segment('3') == '') class="active" @endif >
						<a href="{{ route('admin.project.getIndex') }}">
							<span>Semua</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getActive'))
					<li @if(segment('2') == 'project' and segment('3') == 'active') class="active" @endif >
						<a href="{{ route('admin.project.getActive') }}">
							<span>Accepted</span>
							<span class="badge">{{ $total_active_project }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getReject'))
					<li @if(segment('2') == 'project' and segment('3') == 'rejected') class="active" @endif >
						<a href="{{ route('admin.project.getReject') }}">
							<span>Rejected</span>
							<span class="badge">{{ $total_reject_project }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getPending'))
					<li class="@if(segment(2) == 'project' and segment(3) == 'pending') active @endif">
						<a href="{{ route('admin.project.getPending') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_project }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getFundraiser'))
					<li class="@if(segment(2) == 'project' and segment(3) == 'fundraiser') active @endif">
						<a href="{{ route('admin.project.getFundraiser') }}">
							<span>Fundraiser</span>
							<span class="badge">{{ $total_fundraiser_project }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted('admin.page.getCategories'))
			<li class="@if(segment(2) == 'categories') active @endif">
				<a href="{{ route('admin.page.getCategories') }}">
					<i class="fa fa-tags"></i> Kategori Project / Campaign
				</a>
			</li>
			@endif

			@if (isPermitted('admin.banner.getBanner'))
			<li class="@if(segment(2) == 'banner') active @endif">
				<a href="{{ route('admin.banner.getBanner') }}">
					<i class="fa fa-tags"></i> Banner Foto
				</a>
			</li>
			@endif

			@if (isPermitted('admin.payment_method.getPaymentMethod'))
			<li class="@if(segment(2) == 'payment_method') active @endif">
				<a href="{{ route('admin.payment_method.getPaymentMethod') }}">
					<i class="fa fa-tags"></i> Payment Method
				</a>
			</li>
			@endif
			@endif

			@if (isPermitted([
				'admin.transaksi.getSuccessTransaksi',
				'admin.transaksi.getSuccessTransaksi',
				'admin.transaksi.getPendingTransaksi',
				'admin.transaksi.getExpiredTransaksi',
				'admin.project.getSuccessSupporter',
				'admin.project.getPendingSupporter',
				'admin.project.getExpiredSupporter',
				'admin.donation.getSuccessDonation',
				'admin.donation.getPendingDonation',
				'admin.donation.getExpiredDonation',
				'admin.zakat.getSuccessZakat',
				'admin.zakat.getPendingZakat',
				'admin.zakat.getExpiredZakat',
				'admin.referral.getAll',
				'admin.referral.getDonation',
				'admin.referral.getZakat',
				'admin.withdraw.getSuccessWithdraw',
				'admin.withdraw.getPendingWithdraw',
				'admin.withdraw.getFailedWithdraw',
				'admin.crm.getSuccessTransaksi',
				'admin.crm.getPendingTransaksi',
				'admin.crm.getExpiredTransaksi',
			]))
			<li class="header">Transaksi</li>

			@if (isPermitted('admin.invoice.getDataTransaksi'))
			<li class="@if(segment(2) == 'invoice') active @endif">
				<a href="{{ route('admin.invoice.getDataTransaksi') }}">
					<i class='fa fa-file-text'></i> <span>Data Invoice</span>
				</a>
			</li>
			@endif
			@if (isPermitted([
				'admin.transaksi.getSuccessTransaksi',
				'admin.transaksi.getPendingTransaksi',
				'admin.transaksi.getExpiredTransaksi',
			]))
			<li class="treeview @if(segment(2) == 'transaksi') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Semua Transaksi</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.transaksi.getSuccessTransaksi'))
					<li @if(segment('2') == 'transaksi' and segment('3') == 'success-transaksi') class="active" @endif >
						<a href="{{ route('admin.transaksi.getSuccessTransaksi') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_supporter + $total_success_donation + $total_success_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.transaksi.getPendingTransaksi'))
					<li @if(segment('2') == 'transaksi' and segment('3') == 'pending-transaksi') class="active" @endif >
						<a href="{{ route('admin.transaksi.getPendingTransaksi') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_supporter + $total_pending_donation + $total_pending_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.transaksi.getExpiredTransaksi'))
					<li @if(segment('2') == 'transaksi' and segment('3') == 'expired-transaksi') class="active" @endif >
						<a href="{{ route('admin.transaksi.getExpiredTransaksi') }}">
							<span>Expired</span>
							<span class="badge">{{ $total_expired_supporter + $total_expired_donation + $total_expired_zakat }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.project.getSuccessSupporter',
				'admin.project.getPendingSupporter',
				'admin.project.getExpiredSupporter',
			]))
			<li class="treeview @if(segment(2) == 'project') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Infak Terikat</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.project.getSuccessSupporter'))
					<li @if(segment('2') == 'project' and segment('3') == 'success-supporter') class="active" @endif >
						<a href="{{ route('admin.project.getSuccessSupporter') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_supporter }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getPendingSupporter'))
					<li @if(segment('2') == 'project' and segment('3') == 'pending-supporter') class="active" @endif >
						<a href="{{ route('admin.project.getPendingSupporter') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_supporter }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.project.getExpiredSupporter'))
					<li @if(segment('2') == 'project' and segment('3') == 'expired-supporter') class="active" @endif >
						<a href="{{ route('admin.project.getExpiredSupporter') }}">
							<span>Expired</span>
							<span class="badge">{{ $total_expired_supporter }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.donation.getSuccessDonation',
				'admin.donation.getPendingDonation',
				'admin.donation.getExpiredDonation',
			]))
			<li class="treeview @if(segment(2) == 'donation') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Infak Umum</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.donation.getSuccessDonation'))
					<li @if(segment('2') == 'donation' and segment('3') == 'success-donation') class="active" @endif >
						<a href="{{ route('admin.donation.getSuccessDonation') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_donation }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.donation.getPendingDonation'))
					<li @if(segment('2') == 'donation' and segment('3') == 'pending-donation') class="active" @endif >
						<a href="{{ route('admin.donation.getPendingDonation') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_donation }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.donation.getExpiredDonation'))
					<li @if(segment('2') == 'donation' and segment('3') == 'expired-donation') class="active" @endif >
						<a href="{{ route('admin.donation.getExpiredDonation') }}">
							<span>Expired</span>
							<span class="badge">{{ $total_expired_donation }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.zakat.getSuccessZakat',
				'admin.zakat.getPendingZakat',
				'admin.zakat.getExpiredZakat',
			]))
			<li class="treeview @if(segment(2) == 'zakat') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Zakat</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.zakat.getSuccessZakat'))
					<li @if(segment('2') == 'zakat' and segment('3') == 'success-zakat') class="active" @endif >
						<a href="{{ route('admin.zakat.getSuccessZakat') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.zakat.getPendingZakat'))
					<li @if(segment('2') == 'zakat' and segment('3') == 'pending-zakat') class="active" @endif >
						<a href="{{ route('admin.zakat.getPendingZakat') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.zakat.getExpiredZakat'))
					<li @if(segment('2') == 'zakat' and segment('3') == 'expired-zakat') class="active" @endif >
						<a href="{{ route('admin.zakat.getExpiredZakat') }}">
							<span>Expired</span>
							<span class="badge">{{ $total_expired_zakat }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.referral.getAll',
				'admin.referral.getDonation',
				'admin.referral.getZakat',
			]))
			<li class="treeview @if(segment(2) == 'referral') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Referral</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.referral.getAll'))
					<li @if(segment('2') == 'referral' and segment('3') == 'all') class="active" @endif >
						<a href="{{ route('admin.referral.getAll') }}">
							<span>Semua Transaksi</span>
							<span class="badge">{{ $total_all_referrers }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.referral.getProject'))
					<li @if(segment('2') == 'referral' and segment('3') == 'project') class="active" @endif >
						<a href="{{ route('admin.referral.getProject') }}">
							<span>Infak Terikat</span>
							<span class="badge">{{ $total_supporter_referrers }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.referral.getDonation'))
					<li @if(segment('2') == 'referral' and segment('3') == 'donation') class="active" @endif >
						<a href="{{ route('admin.referral.getDonation') }}">
							<span>Infak Umum</span>
							<span class="badge">{{ $total_donation_referrers }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.referral.getZakat'))
					<li @if(segment('2') == 'referral' and segment('3') == 'zakat') class="active" @endif >
						<a href="{{ route('admin.referral.getZakat') }}">
							<span>Zakat</span>
							<span class="badge">{{ $total_zakat_referrers }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.withdraw.getSuccessWithdraw',
				'admin.withdraw.getPendingWithdraw',
				'admin.withdraw.getFailedWithdraw',
			]))
			<li class="treeview @if(segment(2) == 'withdraw') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>Withdraw</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.withdraw.getSuccessWithdraw'))
					<li @if(segment('2') == 'withdraw' and segment('3') == 'success-withdraw') class="active" @endif >
						<a href="{{ route('admin.withdraw.getSuccessWithdraw') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_withdraws }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.withdraw.getPendingWithdraw'))
					<li @if(segment('2') == 'withdraw' and segment('3') == 'pending-withdraw') class="active" @endif >
						<a href="{{ route('admin.withdraw.getPendingWithdraw') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_withdraws }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.withdraw.getFailedWithdraw'))
					<li @if(segment('2') == 'withdraw' and segment('3') == 'failed-withdraw') class="active" @endif >
						<a href="{{ route('admin.withdraw.getFailedWithdraw') }}">
							<span>Failed</span>
							<span class="badge">{{ $total_failed_withdraws }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if (isPermitted([
				'admin.crm.getSuccessTransaksi',
				'admin.crm.getPendingTransaksi',
				'admin.crm.getExpiredTransaksi',
			]))
			{{-- <li class="treeview @if(segment(2) == 'crm') active @endif">
				<a href="#">
					<i class='fa fa-file-text'></i> <span>CRM</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if (isPermitted('admin.crm.getSuccessTransaksi'))
					<li @if(segment('2') == 'crm' and segment('3') == 'success-crm') class="active" @endif >
						<a href="{{ route('admin.crm.getSuccessTransaksi') }}">
							<span>Success</span>
							<span class="badge">{{ $total_success_supporter + $total_success_donation + $total_success_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.crm.getPendingTransaksi'))
					<li @if(segment('2') == 'crm' and segment('3') == 'pending-crm') class="active" @endif >
						<a href="{{ route('admin.crm.getPendingTransaksi') }}">
							<span>Pending</span>
							<span class="badge">{{ $total_pending_supporter + $total_pending_donation + $total_pending_zakat }}</span>
						</a>
					</li>
					@endif
					@if (isPermitted('admin.crm.getExpiredTransaksi'))
					<li @if(segment('2') == 'crm' and segment('3') == 'expired-crm') class="active" @endif >
						<a href="{{ route('admin.crm.getExpiredTransaksi') }}">
							<span>Expired</span>
							<span class="badge">{{ $total_expired_supporter + $total_expired_donation + $total_expired_zakat }}</span>
						</a>
					</li>
					@endif
				</ul>
			</li> --}}
			@endif
			@endif


			@if (isPermitted('admin.page.getSetting'))
			<li class="@if(segment(2) == 'banner') active @endif">
				<a href="{{ route('admin.page.getSetting') }}">
					<i class="fa fa-cog"></i> Pengaturan
				</a>
			</li>
			@endif
		</ul>
	</section>
</aside>
