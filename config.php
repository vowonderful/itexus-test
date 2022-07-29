<?php

date_default_timezone_set('Europe/Minsk');

/** SQL params */
const _DataBaseParams = array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'itexus',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
);



/** Settings */
const _LANG = 'en'; // en | ru
const _CURRENCY = '$';
const _CURRENCY_ATTACHMENT = 'prefix'; // prefix | postfix
const _SITE_NAME = 'iTexUS'; // [any string]



/** Immutable parameters */
enum Role
{
    case admin;
    case moder;
    case user;
}