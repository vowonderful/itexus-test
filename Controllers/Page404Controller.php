<?php

namespace Controllers;

use Base\General;

class Page404Controller {

    public function main(): void
    {
        $params['_title'] = 'Page Not Found';
        General::displayPage('Page@404', null, $params);
    }

}