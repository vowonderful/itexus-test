<?php

declare( strict_types = 1 );

require_once __DIR__ . '/Base/Autoload.php';
require_once 'config.php';

try {
    \Base\App::run();
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}
