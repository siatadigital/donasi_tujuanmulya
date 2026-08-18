<?php

get('backend/login', [
	'as' => 'admin.user.getLogin',
	'uses' => 'Admin\UserController@getLogin',
	'middleware' => 'guest'
]);
post('backend/login', [
	'as' => 'admin.user.postLogin',
	'uses' => 'Admin\UserController@postLogin',
	'middleware' => 'guest'
]);
get('backend/logout', ['as' => 'admin.user.getLogout', 'uses' => 'Admin\UserController@getLogout']);

get('/test-invoice/{id}', ['as' => 'page.invoice', 'uses' => 'Admin\InvoiceController@putSuccessDonation']);
get('/create-invoice/{id}', ['as' => 'page.invoice', 'uses' => 'Admin\InvoiceController@CreateTransaksiPdf']);
get('/invoice/{id}', ['as' => 'page.invoice', 'uses' => 'Admin\InvoiceController@getSuccessTransaksiPdf']);
get('/invoice_view/{id}', ['as' => 'page.invoice', 'uses' => 'Admin\InvoiceController@getSuccessTransaksiView']);

Route::group(['prefix' => 'backend', 'middleware' => ['admin', 'admin.privilege']], function () {

	get('invoice', ['as' => 'admin.invoice.getDataTransaksi', 'uses' => 'Admin\InvoiceController@getSuccessTransaksi']);

	get('/', ['as' => 'admin.page.getIndex', 'uses' => 'Admin\PageController@getIndex']);
	get('/content-page', ['as' => 'admin.page.getCustomPage', 'uses' => 'Admin\PageController@getCustomPage']);
	put('/content-page', ['as' => 'admin.page.putCustomPage', 'uses' => 'Admin\PageController@putCustomPage']);
	get('setting', ['as' => 'admin.page.getSetting', 'uses' => 'Admin\PageController@getSetting']);
	put('setting', ['as' => 'admin.page.putSetting', 'uses' => 'Admin\PageController@putSetting']);

	get('banner', ['as' => 'admin.banner.getBanner', 'uses' => 'Admin\BannerController@getBanner']);
	post('banner', ['as' => 'admin.banner.postBanner', 'uses' => 'Admin\BannerController@postBanner']);
	delete('banner/{id}', ['as' => 'admin.banner.deleteBanner', 'uses' => 'Admin\BannerController@deleteBanner']);
	get('banner/set-modal-popup/{id}', ['as' => 'admin.banner.setModalPopup', 'uses' => 'Admin\BannerController@setModalPopup']);
	get('banner/remove-modal-popup/{id}', ['as' => 'admin.banner.removeModalPopup', 'uses' => 'Admin\BannerController@removeModalPopup']);

	get('payment_method', ['as' => 'admin.payment_method.getPaymentMethod', 'uses' => 'Admin\PaymentMethodController@getPaymentMethod']);
	get('payment_method/create', ['as' => 'admin.payment_method.getCreate', 'uses' => 'Admin\PaymentMethodController@createPaymentMethod']);
	post('payment_method/store', ['as' => 'admin.payment_method.storeCreate', 'uses' => 'Admin\PaymentMethodController@storePaymentMethod']);
	get('payment_method/edit/{id}', ['as' => 'admin.payment_method.getEdit', 'uses' => 'Admin\PaymentMethodController@editPaymentMethod']);
	post('payment_method/update/{id}', ['as' => 'admin.payment_method.updatePaymentMethod', 'uses' => 'Admin\PaymentMethodController@updatePaymentMethod']);

	get('categories', ['as' => 'admin.page.getCategories', 'uses' => 'Admin\PageController@getCategories']);
	post('categories', ['as' => 'admin.page.postCategories', 'uses' => 'Admin\PageController@postCategories']);
	delete('categories/{id}', ['as' => 'admin.page.deleteCategories', 'uses' => 'Admin\PageController@deleteCategories']);

	get('user', ['as' => 'admin.user.getIndex', 'uses' => 'Admin\UserController@getIndex']);
	get('user/review/{id}', ['as' => 'admin.user.getShow', 'uses' => 'Admin\UserController@getShow']);
	get('user/member', ['as' => 'admin.user.getMember', 'uses' => 'Admin\UserController@getMember']);
	get('user/admin', ['as' => 'admin.user.getAdmin', 'uses' => 'Admin\UserController@getAdmin']);
	get('user/admin/create', ['as' => 'admin.user.createAdmin', 'uses' => 'Admin\UserController@createAdmin']);
	post('user/admin/store', ['as' => 'admin.user.storeAdmin', 'uses' => 'Admin\UserController@storeAdmin']);
	get('user/admin/edit/{id}', ['as' => 'admin.user.editAdmin', 'uses' => 'Admin\UserController@editAdmin']);
	post('user/admin/update/{id}', ['as' => 'admin.user.updateAdmin', 'uses' => 'Admin\UserController@updateAdmin']);
	get('user/admin/delete/{id}', ['as' => 'admin.user.deleteAdmin', 'uses' => 'Admin\UserController@deleteAdmin']);
	get('user/verified-member', ['as' => 'admin.user.getVerify', 'uses' => 'Admin\UserController@getVerify']);

	get('user/pending-verify', ['as' => 'admin.user.getVerifyPending', 'uses' => 'Admin\UserController@getVerifyPending']);
	put('user/pending-verify/{id}', ['as' => 'admin.user.putVerifyPending', 'uses' => 'Admin\UserController@putVerifyPending']);
	put('user/accept-verify/{id}', ['as' => 'admin.user.putVerifyAccept', 'uses' => 'Admin\UserController@putVerifyAccept']);
	put('user/reject-verify/{id}', ['as' => 'admin.user.putVerifyReject', 'uses' => 'Admin\UserController@putVerifyReject']);
	put('user/as-admin/{id}', ['as' => 'admin.user.putAsAdmin', 'uses' => 'Admin\UserController@putAsAdmin']);

	get('blogs', ['as' => 'admin.blogs.getIndex', 'uses' => 'Admin\BlogController@getIndex']);
	get('blogs/{id}/edit', ['as' => 'admin.blogs.getEdit', 'uses' => 'Admin\BlogController@getEdit']);
	post('blogs/{id}/edit', ['as' => 'admin.blogs.postEdit', 'uses' => 'Admin\BlogController@getIndex']);
	get('blogs/{id}/delete', ['as' => 'admin.blogs.getDelete', 'uses' => 'Admin\BlogController@getIndex']);
	post('blogs/{id}/delete', ['as' => 'admin.blogs.postDelete', 'uses' => 'Admin\BlogController@getIndex']);
	get('blogs/publish', ['as' => 'admin.blogs.getPublish', 'uses' => 'Admin\BlogController@getPublish']);
	get('blogs/draft', ['as' => 'admin.blogs.getDraft', 'uses' => 'Admin\BlogController@getDraft']);

	get('events', ['as' => 'admin.events.getIndex', 'uses' => 'Admin\EventController@getIndex']);
	get('events_users/{eventID}', ['as' => 'admin.events.getIndexUser', 'uses' => 'Admin\EventController@getIndexUser']);
	get('events/{id}/edit', ['as' => 'admin.events.getEdit', 'uses' => 'Admin\EventController@getEdit']);
	post('events/{id}/edit', ['as' => 'admin.events.postEdit', 'uses' => 'Admin\EventController@getIndex']);
	get('events/{id}/delete', ['as' => 'admin.events.getDelete', 'uses' => 'Admin\EventController@getIndex']);
	post('events/{id}/delete', ['as' => 'admin.events.postDelete', 'uses' => 'Admin\EventController@getIndex']);


	get('notif/notif_wa', ['as' => 'admin.notif.getIndexNotifWa', 'uses' => 'Admin\NotificationController@getIndexNotifWa']);
	get('notif/notif_wa/{id}/edit', ['as' => 'admin.notif.getEditNotifWa', 'uses' => 'Admin\NotificationController@getEditNotifWa']);
	post('notif/notif_wa/update/{id}', ['as' => 'admin.notif.postEditNotifWa', 'uses' => 'Admin\NotificationController@postEditNotifWa']);

	get('notif/notif_email', ['as' => 'admin.notif.getIndexNotifEmail', 'uses' => 'Admin\NotificationController@getIndexNotifEmail']);
	get('notif/notif_email/{id}/edit', ['as' => 'admin.notif.getEditNotifEmail', 'uses' => 'Admin\NotificationController@getEditNotifEmail']);
	post('notif/notif_email/update/{id}', ['as' => 'admin.notif.postEditNotifEmail', 'uses' => 'Admin\NotificationController@postEditNotifEmail']);

	get('popup/doa_zakat', ['as' => 'admin.popup.getIndexPopupDoaZakat', 'uses' => 'Admin\NotificationController@getIndexPopupDoaZakat']);
	get('popup/doa_zakat/{id}/edit', ['as' => 'admin.popup.getEditPopupDoaZakat', 'uses' => 'Admin\NotificationController@getEditPopupDoaZakat']);
	post('popup/doa_zakat/update/{id}', ['as' => 'admin.popup.postEditPopupDoaZakat', 'uses' => 'Admin\NotificationController@postEditPopupDoaZakat']);

	get('popup/transaksi', ['as' => 'admin.popup.getIndexPopupTransaksi', 'uses' => 'Admin\NotificationController@getIndexPopupTransaksi']);
	get('popup/transaksi/{id}/edit', ['as' => 'admin.popup.getEditPopupTransaksi', 'uses' => 'Admin\NotificationController@getEditPopupTransaksi']);
	post('popup/transaksi/update/{id}', ['as' => 'admin.popup.postEditPopupTransaksi', 'uses' => 'Admin\NotificationController@postEditPopupTransaksi']);

	get('transaksi', ['as' => 'admin.transaksi.getTransaksi', 'uses' => 'Admin\ProjectController@getTransaksi']);
	get('project', ['as' => 'admin.project.getIndex', 'uses' => 'Admin\ProjectController@getIndex']);
	get('project/confirm_check/{id}', ['as' => 'admin.project.confirmCheck', 'uses' => 'Admin\ProjectController@confirmCheck']);
	get('project/cancel_check/{id}', ['as' => 'admin.project.cancelCheck', 'uses' => 'Admin\ProjectController@cancelCheck']);
	post('project/submit_note', ['as' => 'admin.project.submitNote', 'uses' => 'Admin\ProjectController@submitNote']);
	get('project/review/{id}', ['as' => 'admin.project.getShow', 'uses' => 'Admin\ProjectController@getShow']);
	get('project/change-featured/{id}', ['as' => 'admin.project.changeFeatured', 'uses' => 'Admin\ProjectController@changeFeatured']);
	get('project/active', ['as' => 'admin.project.getActive', 'uses' => 'Admin\ProjectController@getActive']);
	get('project/pending', ['as' => 'admin.project.getPending', 'uses' => 'Admin\ProjectController@getPending']);
	get('project/rejected', ['as' => 'admin.project.getReject', 'uses' => 'Admin\ProjectController@getReject']);
	get('project/fundraiser', ['as' => 'admin.project.getFundraiser', 'uses' => 'Admin\ProjectController@getFundraiser']);
	put('project/accept/{id}', ['as' => 'admin.project.putAccept', 'uses' => 'Admin\ProjectController@putAccept']);
	put('project/reject/{id}', ['as' => 'admin.project.putReject', 'uses' => 'Admin\ProjectController@putReject']);
	post('project/success-supporter-export', ['as' => 'admin.project.getSuccessSupporterExport', 'uses' => 'Admin\ProjectController@getSuccessSupporterExport']);
	post('project/pending-supporter-export', ['as' => 'admin.project.getPendingSupporterExport', 'uses' => 'Admin\ProjectController@getPendingSupporterExport']);
	post('project/expired-supporter-export', ['as' => 'admin.project.getExpiredSupporterExport', 'uses' => 'Admin\ProjectController@getExpiredSupporterExport']);
	get('project/success-supporter', ['as' => 'admin.project.getSuccessSupporter', 'uses' => 'Admin\ProjectController@getSuccessSupporter']);
	get('project/pending-supporter', ['as' => 'admin.project.getPendingSupporter', 'uses' => 'Admin\ProjectController@getPendingSupporter']);
	get('project/expired-supporter', ['as' => 'admin.project.getExpiredSupporter', 'uses' => 'Admin\ProjectController@getExpiredSupporter']);
	put('project/accept-supporter/{id}', ['as' => 'admin.project.putAcceptSupporter', 'uses' => 'Admin\ProjectController@putAcceptSupporter']);
	put('project/reject-supporter/{id}', ['as' => 'admin.project.putRejectSupporter', 'uses' => 'Admin\ProjectController@putRejectSupporter']);

	post('donation/success-donation-export', ['as' => 'admin.donation.getSuccessDonationExport', 'uses' => 'Admin\DonationController@getSuccessDonationExport']);
	post('donation/pending-donation-export', ['as' => 'admin.donation.getPendingDonationExport', 'uses' => 'Admin\DonationController@getPendingDonationExport']);
	post('donation/expired-donation-export', ['as' => 'admin.donation.getExpiredDonationExport', 'uses' => 'Admin\DonationController@getExpiredDonationExport']);
	get('donation/success-donation', ['as' => 'admin.donation.getSuccessDonation', 'uses' => 'Admin\DonationController@getSuccessDonation']);
	get('donation/pending-donation', ['as' => 'admin.donation.getPendingDonation', 'uses' => 'Admin\DonationController@getPendingDonation']);
	get('donation/expired-donation', ['as' => 'admin.donation.getExpiredDonation', 'uses' => 'Admin\DonationController@getExpiredDonation']);
	put('donation/accept-donation/{id}', ['as' => 'admin.donation.putSuccessDonation', 'uses' => 'Admin\DonationController@putSuccessDonation']);
	put('donation/reject-donation/{id}', ['as' => 'admin.donation.putRejectDonation', 'uses' => 'Admin\DonationController@putRejectDonation']);
	get('donation/confirm_check/{id}', ['as' => 'admin.donation.confirmCheck', 'uses' => 'Admin\DonationController@confirmCheck']);
	get('donation/cancel_check/{id}', ['as' => 'admin.donation.cancelCheck', 'uses' => 'Admin\DonationController@cancelCheck']);
	post('donation/submit_note', ['as' => 'admin.donation.submitNote', 'uses' => 'Admin\DonationController@submitNote']);

	post('zakat/success-zakat-export', ['as' => 'admin.zakat.getSuccessZakatExport', 'uses' => 'Admin\ZakatController@getSuccessZakatExport']);
	post('zakat/pending-zakat-export', ['as' => 'admin.zakat.getPendingZakatExport', 'uses' => 'Admin\ZakatController@getPendingZakatExport']);
	post('zakat/expired-zakat-export', ['as' => 'admin.zakat.getExpiredZakatExport', 'uses' => 'Admin\ZakatController@getExpiredZakatExport']);
	get('zakat/success-zakat', ['as' => 'admin.zakat.getSuccessZakat', 'uses' => 'Admin\ZakatController@getSuccessZakat']);
	get('zakat/pending-zakat', ['as' => 'admin.zakat.getPendingZakat', 'uses' => 'Admin\ZakatController@getPendingZakat']);
	get('zakat/expired-zakat', ['as' => 'admin.zakat.getExpiredZakat', 'uses' => 'Admin\ZakatController@getExpiredZakat']);
	put('zakat/accept-zakat/{id}', ['as' => 'admin.zakat.putSuccessZakat', 'uses' => 'Admin\ZakatController@putSuccessZakat']);
	put('zakat/reject-zakat/{id}', ['as' => 'admin.zakat.putRejectZakat', 'uses' => 'Admin\ZakatController@putRejectZakat']);
	get('zakat/confirm_check/{id}', ['as' => 'admin.zakat.confirmCheck', 'uses' => 'Admin\ZakatController@confirmCheck']);
	get('zakat/cancel_check/{id}', ['as' => 'admin.zakat.cancelCheck', 'uses' => 'Admin\ZakatController@cancelCheck']);
	post('zakat/submit_note', ['as' => 'admin.zakat.submitNote', 'uses' => 'Admin\ZakatController@submitNote']);

	get('transaksi/success-transaksi-json', ['as' => 'admin.transaksi.getJsonSuccessTransaksi', 'uses' => 'Admin\TransaksiController@getJsonSuccessTransaksi']);
	get('transaksi/pending-transaksi-json', ['as' => 'admin.transaksi.getJsonPendingTransaksi', 'uses' => 'Admin\TransaksiController@getJsonPendingTransaksi']);
	get('transaksi/expired-transaksi-json', ['as' => 'admin.transaksi.getJsonExpiredTransaksi', 'uses' => 'Admin\TransaksiController@getJsonExpiredTransaksi']);
	post('transaksi/success-transaksi-export', ['as' => 'admin.transaksi.getSuccessTransaksiExport', 'uses' => 'Admin\TransaksiController@getSuccessTransaksiExport']);
	post('transaksi/pending-transaksi-export', ['as' => 'admin.transaksi.getPendingTransaksiExport', 'uses' => 'Admin\TransaksiController@getPendingTransaksiExport']);
	post('transaksi/expired-transaksi-export', ['as' => 'admin.transaksi.getExpiredTransaksiExport', 'uses' => 'Admin\TransaksiController@getExpiredTransaksiExport']);
	get('transaksi/success-transaksi', ['as' => 'admin.transaksi.getSuccessTransaksi', 'uses' => 'Admin\TransaksiController@getSuccessTransaksi']);
	get('transaksi/pending-transaksi', ['as' => 'admin.transaksi.getPendingTransaksi', 'uses' => 'Admin\TransaksiController@getPendingTransaksi']);
	get('transaksi/expired-transaksi', ['as' => 'admin.transaksi.getExpiredTransaksi', 'uses' => 'Admin\TransaksiController@getExpiredTransaksi']);

	get('email', ['as' => 'admin.email.getCreate', 'uses' => 'Admin\EmailController@getCreate']);

	get('group_privilege', ['as' => 'admin.group_privilege.getGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@getGroupPrivilege']);
	get('group_privilege/create', ['as' => 'admin.group_privilege.createGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@createGroupPrivilege']);
	post('group_privilege/store', ['as' => 'admin.group_privilege.storeGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@storeGroupPrivilege']);
	get('group_privilege/edit/{id}', ['as' => 'admin.group_privilege.editGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@editGroupPrivilege']);
	post('group_privilege/update/{id}', ['as' => 'admin.group_privilege.updateGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@updateGroupPrivilege']);
	get('group_privilege/delete/{id}', ['as' => 'admin.group_privilege.deleteGroupPrivilege', 'uses' => 'Admin\GroupPrivilegeController@deleteGroupPrivilege']);
	get('group_privilege/details-json', ['as' => 'admin.group_privilege.getJsonGroupPrivilegeDetails', 'uses' => 'Admin\GroupPrivilegeController@getJsonGroupPrivilegeDetails']);

	get('referral/all', ['as' => 'admin.referral.getAll', 'uses' => 'Admin\ReferralController@getAll']);
	post('referral/all-export', ['as' => 'admin.referral.getAllExport', 'uses' => 'Admin\ReferralController@getAllExport']);
	get('referral/project', ['as' => 'admin.referral.getProject', 'uses' => 'Admin\ReferralController@getProject']);
	post('referral/project-export', ['as' => 'admin.referral.getProjectExport', 'uses' => 'Admin\ReferralController@getProjectExport']);
	get('referral/donation', ['as' => 'admin.referral.getDonation', 'uses' => 'Admin\ReferralController@getDonation']);
	post('referral/donation-export', ['as' => 'admin.referral.getDonationExport', 'uses' => 'Admin\ReferralController@getDonationExport']);
	get('referral/zakat', ['as' => 'admin.referral.getZakat', 'uses' => 'Admin\ReferralController@getZakat']);
	post('referral/zakat-export', ['as' => 'admin.referral.getZakatExport', 'uses' => 'Admin\ReferralController@getZakatExport']);

	get('withdraw/success-withdraw-json', ['as' => 'admin.withdraw.getJsonSuccessWithdraw', 'uses' => 'Admin\WithdrawController@getJsonSuccessWithdraw']);
	get('withdraw/pending-withdraw-json', ['as' => 'admin.withdraw.getJsonPendingWithdraw', 'uses' => 'Admin\WithdrawController@getJsonPendingWithdraw']);
	get('withdraw/failed-withdraw-json', ['as' => 'admin.withdraw.getJsonFailedWithdraw', 'uses' => 'Admin\WithdrawController@getJsonFailedWithdraw']);
	get('withdraw/success-withdraw', ['as' => 'admin.withdraw.getSuccessWithdraw', 'uses' => 'Admin\WithdrawController@getSuccessWithdraw']);
	get('withdraw/pending-withdraw', ['as' => 'admin.withdraw.getPendingWithdraw', 'uses' => 'Admin\WithdrawController@getPendingWithdraw']);
	get('withdraw/failed-withdraw', ['as' => 'admin.withdraw.getFailedWithdraw', 'uses' => 'Admin\WithdrawController@getFailedWithdraw']);
	put('withdraw/accept-withdraw/{id}', ['as' => 'admin.withdraw.putSuccessWithdraw', 'uses' => 'Admin\WithdrawController@putSuccessWithdraw']);
	put('withdraw/reject-withdraw/{id}', ['as' => 'admin.withdraw.putRejectWithdraw', 'uses' => 'Admin\WithdrawController@putRejectWithdraw']);

	get('crm/success-transaksi-json', ['as' => 'admin.crm.getJsonSuccessTransaksi', 'uses' => 'Admin\CrmController@getJsonSuccessTransaksi']);
	get('crm/pending-transaksi-json', ['as' => 'admin.crm.getJsonPendingTransaksi', 'uses' => 'Admin\CrmController@getJsonPendingTransaksi']);
	get('crm/expired-transaksi-json', ['as' => 'admin.crm.getJsonExpiredTransaksi', 'uses' => 'Admin\CrmController@getJsonExpiredTransaksi']);
	post('crm/success-transaksi-export', ['as' => 'admin.crm.getSuccessTransaksiExport', 'uses' => 'Admin\CrmController@getSuccessTransaksiExport']);
	post('crm/pending-transaksi-export', ['as' => 'admin.crm.getPendingTransaksiExport', 'uses' => 'Admin\CrmController@getPendingTransaksiExport']);
	post('crm/expired-transaksi-export', ['as' => 'admin.crm.getExpiredTransaksiExport', 'uses' => 'Admin\CrmController@getExpiredTransaksiExport']);
	get('crm/success-transaksi', ['as' => 'admin.crm.getSuccessTransaksi', 'uses' => 'Admin\CrmController@getSuccessTransaksi']);
	get('crm/pending-transaksi', ['as' => 'admin.crm.getPendingTransaksi', 'uses' => 'Admin\CrmController@getPendingTransaksi']);
	get('crm/expired-transaksi', ['as' => 'admin.crm.getExpiredTransaksi', 'uses' => 'Admin\CrmController@getExpiredTransaksi']);
	post('crm/send-message', ['as' => 'admin.crm.sendMessage', 'uses' => 'Admin\CrmController@sendMessage']);
});
