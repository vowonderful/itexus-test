<?php

namespace Base;

use JetBrains\PhpStorm\NoReturn;

class General
{

    public static function in(string $value): string
    {
        return htmlentities($value, ENT_QUOTES & ENT_SUBSTITUTE & ENT_HTML5, 'UTF-8');
    }

    public static function out(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES & ENT_SUBSTITUTE & ENT_HTML5);
    }

    public static function alert(?string $message = null, string $status = 'success'): bool|array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('TEXUSESS');
            session_start();
        }

        if ($message) {
            $_SESSION['alert_text'] = $message;
            $_SESSION['alert_status'] = $status;
            return false;
        } else {
            if (!empty($_SESSION['alert_text']) && !empty($_SESSION['alert_status'])) {

                $message = $_SESSION['alert_text'];
                $status = $_SESSION['alert_status'];
                unset($_SESSION['alert_text']);
                unset($_SESSION['alert_status']);

                return [$message, $status];
            }
        }
        return false;
    }

    #[NoReturn]
    public static function redirect(string $page = '', int $code = 302): void
    {
        if ($page === '/') $page = '';

        header('Location: /' . $page, true, $code);
        exit;
    }

    public static function session(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('TEXUSESS');
        }
        session_start();
    }

    public static function isAuth(): bool
    {
        return (!empty($_SESSION['authorize']) && !empty($_SESSION['user_id']));
    }

    public static function getID(): bool|int
    {
        return !empty($_SESSION['authorize']) ? intval($_SESSION['user_id']) : false;
    }

    public static function getRole(): mixed
    {
        return !empty($_SESSION['role']) ? $_SESSION['role'] : false;
    }

    public static function isRole(\Role $role): bool
    {
        return !empty($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    public static function displayPage(string $page = 'Main@Main', mixed $data = [], mixed $pageParams = []): void
    {
        $data = !empty($data) ? $data : [];

        list ($message, $status) = General::alert();

        try {
            View::render($page, [
                'data' => $data ?? [],
                'auth' => General::isAuth(),
                'messageText' => $message ?? '',
                'messageStatus' => $status ?? ''
            ],
            $pageParams);
        } catch (\ErrorException $e) {
            echo $e;
        }
    }

    /**
     * Accuracy before display is 5 decimal places (divided by 100,000).
     * That is, the user does not see the last three digits of the number
     * (they are needed for higher accuracy of calculations).
     * The next two numbers are user-visible fractional parts (two decimal places).
     * @param int $money The initial value over which mathematical operations are performed
     * @return string A number in a form that is convenient and understandable to a person
     *                (only for display to the user).
     */
    public static function displayMoney(int $money): string
    {
        if ($money < 1000) {
            $value = 0;
        } else {
            $value = number_format($money / 100000, 2, ',', '.');
        }

        if ( _CURRENCY_ATTACHMENT === 'prefix' )
            return '<span class="unallocated">' . _CURRENCY . ' </span>' . $value;
        else if ( _CURRENCY_ATTACHMENT === 'postfix' )
            return $value . '<span class="unallocated"> ' . _CURRENCY . '</span>';
        else
            return $value;
    }

    /**
     * Calculates the number of days between the current date and the specified date
     * @param string $date Set date
     * @return int Difference in days or false if the dates are the sam
     */
    public static function diffDays(string $date): int
    {
        $from = explode('-', date('Y-m-d'));
        $till = explode('-', $date);

        $from = mktime(0, 0, 0, $from[1], $from[2], 0);
        $till = mktime(0, 0, 0, $till[1], $till[2], 0);

        return floor(($till - $from) / (60 * 60 * 24));
    }

    /**
     * Calculates the number of years between the current date and the specified date
     * @param string $date Set date
     * @return int Difference in years or false if the dates are the sam
     */
    public static function diffYears(string $date): int
    {
        $from = explode('-', date('Y-m-d'));
        $till = explode('-', $date);

        $from = mktime(0, 0, 0, $from[1], $from[2], $from[0]);
        $till = mktime(0, 0, 0, $till[1], $till[2], $till[0]);

        return floor( ( ($till - $from) / (60 * 60 * 24 * 365)  ) * -1 );
    }

    /**
     * Selects a word with the correct ending for a given number
     * @param int $n The number to which you need to substitute the word with the correct ending
     * @param array $wordForms Array of words with declension forms
     *                         (two elements for English and three elements for Russian)
     * Example: RU: ([одна, пять, много]) => ['год','года','лет'], ['минута','минуты','минут']...
     * Example: EN: ([one, many/zero]) ['minute','minutes'], ['year','years']...
     * @return string Returns a word in the correct declension for a given number
     */
    public static function declensions(int $n, array $wordForms): string
    {
        if (count($wordForms) === 3) { // For Russian words
            $n = abs($n) % 100;
            $n1 = $n % 10;
            if ($n > 10 && $n < 20) { return $wordForms[2]; }
            if ($n1 > 1 && $n1 < 5) { return $wordForms[1]; }
            if ($n1 == 1) { return $wordForms[0]; }

            return $wordForms[2];
        } else { // For English words
            $n = $n % 10;
            if ($n == 1) { return $wordForms[0]; }
            return $wordForms[1];
        }
    }

    public static function choosingCongratulation(string $birthday): string
    {
        $congratulation = '';
        $bdViaCount = General::diffDays($birthday);
        $bdViaDecl = General::declensions($bdViaCount, ['day', 'days']);
        if ( $bdViaCount <= 8 && $bdViaCount >= 6) {
            $congratulation = "It's your birthday in just a week";
        } else if ( $bdViaCount <= 5 && $bdViaCount >= 3 ) {
            $congratulation = "Your birthday is in just {$bdViaCount} {$bdViaDecl}!";
        } else if ( $bdViaCount === 2 ) {
            $congratulation = "The day after tomorrow is your birthday!";
        } else if ( $bdViaCount === 1 ) {
            $congratulation = "Tomorrow is your birthday!";
        } else if ( $bdViaCount === 0 ) {
            $congratulation = "It's your birthday today! Congratulations!";
        } else if ( $bdViaCount === -1 ) {
            $congratulation = "Yesterday was your birthday! Congratulations!";
        }
        return $congratulation;
    }

    #[NoReturn]
    public static function accessDenied(): void
    {
        $params['_title'] = 'Forbidden';

        header("HTTP/1.1 403 Not Found");
        General::displayPage('Page@403', null, $params);
        exit;
    }

    public static function setDefaultVariables(array &$data): void
    {
        $data['_lang'] = !empty($data['_lang']) ? $data['_lang'] : _LANG;
        $data['_title'] = !empty($data['_title']) ? $data['_title'] : _SITE_NAME;
        $data['_theme-color'] = !empty($data['_theme-color']) ? $data['_theme-color'] : '#fff';
        $data['_amwasbs'] = !empty($data['_amwasbs']) ? $data['_amwasbs'] : 'black-translucent';
    }

}