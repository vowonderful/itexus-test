<?php

namespace Controllers;

use Base\General;
use Base\View;
use JetBrains\PhpStorm\NoReturn;

class MainController {

    public function main(): void
    {
        $params['_title'] = _SITE_NAME;

        $model = new \Models\MainModel();
        $result = $model->getLastUsers(10);

        $db['users'] = $result;

        General::displayPage('Main@Main', $db, $params);
    }

    #[NoReturn]
    public function redirect(): void
    {
        General::redirect('/', 301);
    }

}