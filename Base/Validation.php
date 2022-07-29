<?php

namespace Base;

class Validation {

    public static function checkPatterns(string $type, string $value): bool|int
    {
        return preg_match(match (strtolower($type)) {
            'username' => '/^[a-zA-Z0-9-_]{3,30}$/',
            'safechars' => '/^[a-zA-Z0-9-_.=+*%#@$!?,;: ]+$/u',
            'email' => '/^[a-zA-Z0-9.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            'name' => '/^[A-Za-zА-Яа-яЁёЙй0-9-\' ]{2,40}$/u',
            'date' => '/^[0-9- \/.]{8,12}$/',
            'country' => '/^[a-zA-ZА-Яа-яЁёЙй0-9-().,\'\/ ]{2,20}$/u',
            'address' => '/^[a-zA-ZА-Яа-яЁёЙй0-9-().,\'\/ ]{0,70}$/u',
            'password' => '/^[a-zA-Z0-9-_.=+*%#@$!?,;:]{0,50}$/',
            default => "",
        }, $value);
    }

    /**
     * Check login and password
     *
     * @param string $username User-specified username
     * @param string $password User-specified password
     * @return string Returns a list of all detected errors
     */
    public static function checkLoginAndPassword(string $username, string $password): string
    {
        $error = '';
        if ( empty($username) && empty($password) ) {
            $error = "You didn't specify your username and password.";
        }
        return $error;
    }

    /**
     * Check login
     * @param string $username User-specified login
     * @return string Returns a list of all detected errors
     */
    public static function checkLogin(string $username): string
    {
        $errors = [];
        if ( empty($username) ) {
            array_push($errors, "You didn't specify a login.");
        } else {
            if (mb_strlen($username) < 3) {
                array_push($errors, "Login is too short (must be at least three characters).");
            } else if (mb_strlen($username) > 30) {
                array_push($errors, "Login is too long (there should be no more than thirty characters).");
            }
            if (!Validation::checkPatterns('username', $username)) {
                array_push($errors, "Invalid characters in the login.");
            }
        }
        return implode(' ', $errors);
    }

    /**
     * Check Password
     * @param string $password User-specified password
     * @return string Returns a list of all detected errors
     */
    public static function checkPassword(string $password): string
    {
        $errors = [];
        if ( empty($password) ) {
            array_push($errors, "You didn't specify a password.");
        } else {
            if ( mb_strlen($password) < 4 ) {
                array_push($errors, "Password is too short (must be at least three characters).");
            } else if ( mb_strlen($password) > 50) {
                array_push($errors, "Password is too long (there should be no more than thirty characters).");
            }
            if (!Validation::checkPatterns('password', $password)) {
                // Restrictions in Base/General/checkValidation()
                array_push($errors, "Invalid characters in the password.");
            }
        }
        return implode(' ', $errors);
    }

    public static function checkEmail(string $email): string
    {
        $error = '';
        if (mb_strlen($email) < 6) {
            $error = 'Email is too short (must be at least 6 characters). ';
        } else if (mb_strlen($email) > 30) {
            $error = 'Email is too long (maximum 60 characters allowed). ';
        } else if (!Validation::checkPatterns('email', $email)) {
            $error = 'The email has the wrong format. ';
        }
        return $error;
    }

    public static function checkCountry(string $country): string
    {
        $error = '';
        if (mb_strlen($country) < 2) {
            $error = 'Country is too short (must be at least 2 characters). ';
        } else if (mb_strlen($country) > 20) {
            $error = 'Country is too long (maximum 20 characters allowed). ';
        } else if (!Validation::checkPatterns('country', $country)) {
            $error = 'The country has the wrong format. ';
        }
        return $error;
    }

    public static function checkBirthday(string $birthday): string
    {
        $error = '';
        if (mb_strlen($birthday) < 8) {
            $error = 'Birthday is too short (must be at least 8 characters). ';
        } else if (mb_strlen($birthday) > 12) {
            $error = 'Birthday is too long (maximum 12 characters allowed). ';
        } else if (!Validation::checkPatterns('date', $birthday)) {
            $error = 'The birthday has the wrong format. ';
        }
        return $error;
    }

    public static function checkName(string $name): string
    {
        $error = '';
        if (mb_strlen($name) < 2) {
            $error = 'Name is too short (must be at least 8 characters). ';
        } else if (mb_strlen($name) > 40) {
            $error = 'Name is too long (maximum 12 characters allowed). ';
        } else if (!Validation::checkPatterns('name', $name)) {
            $error = 'The Name has the wrong format. ';
        }
        return $error;
    }


}