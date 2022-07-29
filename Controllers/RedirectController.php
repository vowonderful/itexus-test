<?php

namespace Controllers;

use Base\General;
use JetBrains\PhpStorm\NoReturn;

class RedirectController {

    #[NoReturn]
    public function User_main(): void
    {
        General::redirect('account', 301);
    }

    #[NoReturn]
    public function User_userInfo($id): void
    {
        General::redirect('account/'.$id, 301);
    }

    #[NoReturn]
    public function Authorize_signup(): void
    {
        General::redirect('signup', 301);
    }

}