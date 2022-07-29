<?php

namespace Router;

class Routes {

    public function __construct()
    {

        Router::get('/', 'Controllers\MainController@main');
        Router::get('404', 'Controllers\Page404Controller@main');

        Router::any('login', 'Controllers\AuthorizeController@login');
        Router::get('logout', 'Controllers\AuthorizeController@logout');
        Router::get('exit', 'Controllers\AuthorizeController@exitPage');

        Router::any('signup', 'Controllers\AuthorizeController@signup');

        Router::get('main', 'Controllers\MainController@redirect');

        Router::get('account', 'Controllers\AccountController@main');
        Router::any('settings', 'Controllers\AccountController@settings');
        Router::get('account/(:num)', 'Controllers\AccountController@userInfo');

        Router::get('account/(:num)/block', 'Controllers\AccountController@userBlock');
        Router::get('account/(:num)/unblock', 'Controllers\AccountController@userUnblock');

        // Redirects:
        Router::get('profile', 'Controllers\RedirectController@User_main');
        Router::get('profile/(:num)', 'Controllers\RedirectController@User_userInfo');
        Router::any('registration', 'Controllers\RedirectController@Authorize_signup');

        Router::dispatch();

    }

}
