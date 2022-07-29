<?php

/*
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#example-implementation
 */
function autoload($className): void
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $nameSpace = '';
    $lastPos = strrpos($className, '\\');

    if ($lastPos) {
        $nameSpace = substr($className, 0, $lastPos);
        $className = substr($className, $lastPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $nameSpace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require_once $fileName;
}

spl_autoload_register('autoload');