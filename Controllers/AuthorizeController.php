<?php

namespace Controllers;

use Base\Validation;
use Base\General;
use Base\PassOptions;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;

class AuthorizeController {

    public function signup(): void
    {
        $params['_title'] = 'Registration';

        if ( General::isAuth() ) {
            General::redirect('account', 303);
        }

        // Checking whether all parameters are specified:
        if ( empty($_POST) ||
            empty($_POST['username']) && empty($_POST['password'])
        ) {
            General::displayPage('Authorize@Signup', $this->getUserParams(), $params);
            return;
        }


        // Checking for errors in the parameters:
        $errorText = '';
        $errorText .= Validation::checkLoginAndPassword($_POST['username'], $_POST['password']);
        $errorText .= Validation::checkLogin($_POST['username']);
        $errorText .= Validation::checkPassword($_POST['password']);
        if ( !empty($errorText) ) {

            General::alert($errorText, 'error');
            General::displayPage('Authorize@Signup', $this->getUserParams(), $params);

            return;
        }


        // Checking for the existence of a user with such a login:
        $model = new \Models\AuthorizeModel();
        $db = $model->usernameBusyCheck(General::in($_POST['username']), true);

        if ( !empty($db) ) {

            $data['_title'] = 'Registration';
            $errorText = 'A user with this username already exists. Please choose another one.';

            General::alert($errorText, 'error');
            General::displayPage('Authorize@Signup', $this->getUserParams(), $params);

            return;

        } else {

            $model = new \Models\AuthorizeModel();
            $data = [
                'username' => General::in($_POST['username']),
                'password' => PassOptions::createPass(General::in($_POST['password'])),
                'email' => '',
                'date_reg' => date("Y-m-d H:i:s"),
                'date_birth' => null,
                'country' => '',
                'name' => '',
                'status' => 'active',
                'role' => 'user',
                'balance' => 0,
            ];

            $db = $model->newRegistration($data);


            // Verification of registration success:
            $model = new \Models\AuthorizeModel();
            $db = $model->usernameBusyCheck(General::in($_POST['username']), true);

            if ( empty($db) ) {

                $errorText = 'Unfortunately, the account could not be registered.';
                General::alert($errorText, 'error');
                General::displayPage('Authorize@Signup', $this->getUserParams(), $params);
                return;

            } else {

                $this->userAuthorize(intval($db['user_id']), $db['role']);

                General::alert('Your account has been successfully registered!', 'success');
                General::redirect('account', 303);

            }
        }
        return;
    }

    public function login(): void
    {
        $params['_title'] = 'Log in';

        if ( General::isAuth() ) {
            General::redirect('account', 303);
        }

        // Checking whether all parameters are specified:
        if ( empty($_POST) ||
            empty($_POST['username']) && empty($_POST['password'])
        ) {
            General::displayPage('Authorize@Login', null, $params);
            return;
        }

        // Checking for errors in the parameters:
        $errorText = '';
        $errorText .= Validation::checkLoginAndPassword($_POST['username'], $_POST['password']);
        $errorText .= Validation::checkLogin($_POST['username']);
        $errorText .= Validation::checkPassword($_POST['password']);

        if ( !empty($errorText) ) {

            General::alert($errorText, 'error');
            General::displayPage('Authorize@Login', $this->getUserParams(), $params);

            return;
        }


        // Checking for the existence of a user with such a login:
        $model = new \Models\AuthorizeModel();
        $user = $model->getPasswordByUsername(General::in($_POST['username']), true);

        // If the user is not found:
        if ( empty($user) ) {

            General::alert('There is no user with this username.', 'error');
            General::displayPage('Authorize@Login', $this->getUserParams(), $params);

        } else {

            // Checking the password match:
            if (!PassOptions::checkVerifyPass(General::in($_POST['password']), General::out($user['password']))) {

                General::alert('Invalid password.', 'error');
                General::displayPage('Authorize@Login', $this->getUserParams(), $params);

            } else {

                // Checking the need to update the password:
                /*
                if (PassOptions::checkNeedsRehash($_POST['password'])) {
                    $newPassHash = PassOptions::createPass(General::in($_POST['password']));

                    $model = new \Models\AuthorizeModel();
                    $model->rehashUserPassword(General::in($_POST['username']), $newPassHash);
                }*/

                $this->userAuthorize(intval($user['user_id']), $user['role']);

                General::alert('You are successfully logged in!', 'success');
                General::redirect('account', 303);

            }
            return;
        }

    }

    #[NoReturn]
    public function logout(): void
    {
        General::alert('You are logged out', 'success');

        //session_destroy();

        unset($_SESSION['user_id']);
        unset($_SESSION['authorize']);
        unset($_SESSION['role']);
        unset($_SESSION);

        General::redirect('/', 303);
    }

    public function exitPage(): void
    {
        $params['_title'] = 'Log out?';

        if ( !General::isAuth() ) {
            General::displayPage('Authorize@Login', null, $params);
            return;
        }

        General::displayPage('Authorize@Exit', null, $params);
    }

    private function userAuthorize(int $userID, string $role = 'user'): void
    {
        if ( session_status() !== PHP_SESSION_ACTIVE ) {
            session_name('TEXUSESS');
            session_start();
        }

        $_SESSION['user_id'] = $userID;
        $_SESSION['authorize'] = true;
        $_SESSION['role'] = constant("Role::{$role}");

    }


    #[ArrayShape(['username' => "string", 'password' => "mixed|string"])]
    private function getUserParams(): array
    {
        return [
            'username' => !empty($_POST['username']) ? General::in($_POST['username']) : '',
            'password' => !empty($_POST['password']) ? $_POST['password'] : '',
        ];
    }

}