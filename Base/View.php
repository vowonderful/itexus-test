<?php

namespace Base;

use ErrorException;

/**
 * This class chooses which view to show.
 */
class View {

    /**
     * @throws ErrorException
     */
    public static function render(string $view, array $data = [], array $pageParams = []): void
    {
        $part['content'] = __DIR__ . '/../Views/' . $view . '.php';
        $part['template'] = __DIR__ . '/../Views/Templates/Template.php';
        $part['menu'] = __DIR__ . '/../Views/Templates/Menu.php';
        $part['notify'] = __DIR__ . '/../Views/Templates/Notify.php';
        $part['scripts'] = __DIR__ . '/../Views/Templates/Scripts.php';
        $part['footer'] = __DIR__ . '/../Views/Templates/Footer.php';

        if ( !file_exists($part['content']) ) {
            throw new ErrorException('View not found');
        }

        // We make the transmitted data available in the view:
        if ( !empty($data) ) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        General::setDefaultVariables($pageParams);

        include_once $part['template'];
        //die;
    }

}