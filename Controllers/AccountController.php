<?php

namespace Controllers;

use Base\General;
use Base\PassOptions;
use Base\Validation;
use JetBrains\PhpStorm\NoReturn;

class AccountController {

    public function main(): void
    {
        if (!General::isAuth()) {
            General::accessDenied();
            return;
        }

        $this->userInfo(General::getID(), true);
    }

    public function userInfo(int $id, bool $canonical = false): void
    {
        $params = [];
        $params['_title'] = 'User profile';

        if (!General::isAuth()) {
            General::accessDenied();
            return;
        }

        $additionally = [];
        $isAccessAllowed = false;
        if ( General::isRole(\Role::admin) || General::getID() === $id )
        {
            array_push($additionally, 'balance');
            $isAccessAllowed = true;
        }

        $model = new \Models\AccountModel();
        $result = $model->getUserByID($id, $additionally);

        // Data preparation:
        if ( !empty($result) ) {

            // Balance:
            if ( $isAccessAllowed && isset($result['balance']) ) {
                $result['balance'] = General::displayMoney($result['balance']);
            }

            // Birthday:
            if ( isset($result['date_birth'] ) ) {
                // Your birthday is in just
                $congratulation = General::choosingCongratulation($result['date_birth']);
                if ( !empty($congratulation) ) {
                    $result['birthday_last'] = $congratulation;
                }
            }

            // Age:
            if ( isset($result['date_birth'] ) ) {
                // Your birthday is in just
                $age = General::diffYears($result['date_birth']);
                if ( !empty($age) ) {
                    $result['age'] = $age . ' ' . General::declensions($age, ['year', 'years']);
                }
            }

        }

        $params['canonical'] = $canonical;

        General::displayPage('Account@User', $result, $params);
    }

    public function settings(int $id = 0): void
    {
        $params['_title'] = 'Settings';

        if ( !General::isAuth()) {
            General::accessDenied();
            return;
        }

        if ( empty($id) ) {
            $id = General::getID();
        }

        $model = new \Models\AccountModel();

        // We save the data if there are no errors:
        if ( !$this->hasError($id, $_POST) ) {
            $this->save($id, $_POST);
        }

        // Data preparation:
        $db = $model->getUserByID($id);

        if ( $db['status'] === 'blocked' ) {
            General::accessDenied();
            return;
        }

        if ( !empty($db) ) {
            $db['birthday'] = !empty($db['date_birth']) ? $db['date_birth'] : '';
            unset($db['date_birth']);
        }

        General::displayPage('Account@Settings', $db, $params);
    }

    #[NoReturn]
    public function userBlock(int $id): void
    {
        if ( !General::isAuth() || !General::isRole(\Role::admin) ) {
            General::redirect('404', 301);
        }

        $model = new \Models\AccountModel();

        $model->userBlock($id);
        $result = $model->getUserStatus($id);

        if ( !empty($result) && $result['status'] === 'blocked' ) {
            General::alert('The user has been successfully blocked!', 'success');
        } else {
            General::alert('Failed to block user', 'error');
        }

        General::redirect('account/' . $id, 303);
    }

    #[NoReturn]
    public function userUnblock(int $id): void
    {
        if ( !General::isAuth() || !General::isRole(\Role::admin) ) {
            General::redirect('404', 301);
        }

        $model = new \Models\AccountModel();

        $model->userUnblock($id);
        $result = $model->getUserStatus($id);

        if ( !empty($result) && $result['status'] === 'active' ) {
            General::alert('The user has been successfully unblocked', 'success');
        } else {
            General::alert('The user could not be unblocked', 'error');
        }

        General::redirect('account/' . $id, 303);
    }


    protected function hasError(int $id, array $data = []): bool
    {
        if ( empty($data) )
            return true;

        $data['name'] = empty($data['name']) ? '' : General::in($data['name']);
        $data['birthday'] = empty($data['birthday']) ? '' : General::in($data['birthday']);
        $data['country'] = empty($data['country']) ? '' : General::in($data['country']);
        $data['email'] = empty($data['email']) ? '' : General::in($data['email']);
        $data['password'] = empty($data['password']) ? '' : $data['password'];

        $errorText = '';
        if ( !empty($data['password']) ) {
            $errorText .= Validation::checkPassword($data['password']);
        }
        if ( !empty($data['email']) ) {
            $errorText .= Validation::checkEmail($data['email']);
        }
        if ( !empty($data['country']) ) {
            $errorText .= Validation::checkCountry($data['country']);
        }
        if ( !empty($data['birthday']) ) {
            $errorText .= Validation::checkBirthday($data['birthday']);
        }
        if ( !empty($data['name']) ) {
            $errorText .= Validation::checkName($data['name']);
        }

        if ( !empty($errorText) ) {
            General::alert($errorText, 'error');
            return true;
        }

        return false;
    }


    protected function save(int $id, array $data = []): void
    {
        // Client device settings are different:
        $data['birthday'] = str_ireplace(".", "-", $data['birthday']);
        $data['birthday'] = str_ireplace("/'", "-", $data['birthday']);


        $data['password'] = !empty($data['password']) ? PassOptions::createPass(General::in($_POST['password'])) : '';
        $data['email'] = General::in($data['email']);
        $data['country'] = General::in($data['country']);
        $data['birthday'] = !empty($data['birthday']) ? General::in($data['birthday']) : NULL;
        $data['name'] = General::in($data['name']);

        $model = new \Models\AccountModel();
        $model->accountUpdate($id, $data);
        $result = $model->getUpdateParams($id, true);
        $result = $this->checkUpdateParams($data, $result);

        if ( !empty($result) ) {
            General::alert('Data successfully updated', 'success');
        } else {
            General::alert('Failed to update data', 'error');
        }

    }

    private function checkUpdateParams(array $userParams, array $databaseParams): bool
    {
        if ($userParams['name'] !== General::out($databaseParams['name'])) {
            return false;
        } else if ($userParams['email'] !== General::out($databaseParams['email'])) {
            return false;
        } else if ($userParams['country'] !== General::out($databaseParams['country'])) {
            return false;
        } else if ((string)$userParams['birthday'] !== General::out((string)$databaseParams['date_birth'])) {

            return false;
        } else if (PassOptions::checkVerifyPass($userParams['password'], General::out($databaseParams['password']))) {
            return false;
        }

        return true;
    }

}