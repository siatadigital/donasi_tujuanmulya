<?php

view()->composer('*', function ($view) {
    $view->with([
        'auth' => auth()->user(),
    ]);
});

view()->composer('contents.user._cover', function ($view) {
    $repoUser = app('UserRepository');
    $user = $repoUser->findByUsername(segment(2));

    $view->with([
        // 'total_blog' => $repoUser->countBlog($user, 'all'),
        'total_project' => $repoUser->countProject($user, 'all'),
        'total_supporting' => $repoUser->countSupportingProject($user),
        'total_media' => $repoUser->countMedia($user),
    ]);
});

view()->composer('admin::partials.side_left', function ($view) {
    $totalSupporterReferrers = app('App\Models\Supporter')
                               ->whereNotNull('code_referral')
                               ->where('status', 'accept')
                               ->count();

    $totalDonationReferrers = app('App\Models\Donation')
                              ->whereNotNull('code_referral')
                              ->where('status', 'success')
                              ->count();

    $totalZakatReferrers = app('App\Models\Zakat')
                           ->whereNotNull('code_referral')
                           ->where('status', 'success')
                           ->count();

    $totalAllReferrers = $totalSupporterReferrers + $totalDonationReferrers + $totalZakatReferrers;

    $view->with([
        'total_pending_verified' => app('App\Models\User')->where('is_verified', 2)->count(),
        'total_verify' => app('App\Models\User')->where('is_superadmin', 0)->where('is_verified', 1)->count(),
        'total_member' => app('App\Models\User')->where('is_superadmin', 0)->where('is_verified', 0)->count(),
        'total_admin' => app('App\Models\User')->where('is_superadmin', 1)->count(),
        'total_blog_publish' => app('App\Models\Blog')->where('status', 'publish')->count(),
        'total_blog_draft' => app('App\Models\Blog')->where('status', 'draft')->count(),
        'total_pending_project' => app('App\Models\Project')->where('status', 'pending')->count(),
        'total_active_project' => app('App\Models\Project')->where('status', 'active')->count(),
        'total_reject_project' => app('App\Models\Project')->where('status', 'reject')->count(),
        'total_fundraiser_project' => app('App\Models\Project')->where('status', 'active')->where('fundraiser_project_id', '<>', 'NULL')->where('fundraiser_project_id', '<>', '0')->count(),
        'total_success_supporter' => app('App\Models\Supporter')->success()->count(),
        'total_pending_supporter' => app('App\Models\Supporter')->pending()->count(),
        'total_expired_supporter' => app('App\Models\Supporter')->expired()->count(),
        'total_success_donation' => app('App\Models\Donation')->success()->count(),
        'total_pending_donation' => app('App\Models\Donation')->pending()->count(),
        'total_expired_donation' => app('App\Models\Donation')->expired()->count(),
        'total_success_zakat' => app('App\Models\Zakat')->success()->count(),
        'total_pending_zakat' => app('App\Models\Zakat')->pending()->count(),
        'total_expired_zakat' => app('App\Models\Zakat')->expired()->count(),
        'total_donation_referrers' => $totalDonationReferrers,
        'total_zakat_referrers' => $totalZakatReferrers,
        'total_supporter_referrers' => $totalSupporterReferrers,
        'total_all_referrers' => $totalAllReferrers,
        'total_success_withdraws' => app('App\Models\ProjectWithdraw')->success()->count(),
        'total_pending_withdraws' => app('App\Models\ProjectWithdraw')->pending()->count(),
        'total_failed_withdraws' => app('App\Models\ProjectWithdraw')->failed()->count(),
    ]);
});

// macros
/**
 * Register Macros
 */
Form::macro('link', function ($text, $method, $action, $attr = array(), $confirm_message = false) {
    // attribute for form
    $formAttr = array('method' => $method, 'url' => $action, 'style' => 'display:inline-block;');

    if (!empty($attr['form_class'])) {
        $formAttr['class'] = $attr['form_class'];
    }

    // append onSubmit
    if ($confirm_message) {
        $confirm_message = is_string($confirm_message) ? $confirm_message : 'are you sure ?';
        $formAttr = array_merge($formAttr, array('onsubmit' => 'return confirm("' . $confirm_message . '");'));
    }

    $output = Form::open($formAttr);

    $output .= '<button type="submit"';
    // give attributes
    if (!empty($attr) and is_array($attr)) {
        foreach ($attr as $key => $value) {
            if ($key != 'icon') {
                $output .= ' ' . $key . '="' . $value . '" ';
            }
        }
    }
    $output .= '>';

    if (isset($attr['icon'])) {
        $output .= '<i class="' . $attr['icon'] . '"></i> ';
    }

    $output .= $text . '</button>';

    $output .= Form::close();

    return $output;
});
