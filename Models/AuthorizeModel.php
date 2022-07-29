<?php

namespace Models;

use Base\Database;
use PDO;


class AuthorizeModel {

    public function newRegistration(array $data): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];
        
        $sql = "INSERT INTO `users` (`username`, `password`, `email`, `date_reg`, `date_birth`, `country`, `name`, `status`, `role`, `balance`) 
                VALUES (:username, :password, :email, :date_reg, :date_birth, :country, :name, :status, :role, :balance)";

        $stmt = $_DB->PDO()->prepare($sql);

        $stmt->bindParam(":username", $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":date_reg", $data['date_reg'], PDO::PARAM_STR);
        $stmt->bindParam(":date_birth", $data['date_birth'], PDO::PARAM_NULL);
        $stmt->bindParam(":country", $data['country'], PDO::PARAM_STR);
        $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(":status", $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(":role", $data['role'], PDO::PARAM_STR);
        $stmt->bindParam(":balance", $data['balance'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function usernameBusyCheck(string $username, bool $getRole = false): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sqlRole = $getRole ? ', `role`' : '';

        $sql = "SELECT `user_id`" . $sqlRole . " FROM `users` WHERE `username` = :username";
        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->execute(['username' => $username]);

        //return $stmt->rowCount();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPasswordByUsername(string $username, bool $getRole = false): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sqlRole = $getRole ? ', `role`' : '';

        $sql = "SELECT `user_id`, `password`" . $sqlRole . " FROM `users` WHERE `username` = :username";
        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->execute(['username' => $username]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function rehashUserPassword(string $username, string $password): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sql = "UPDATE `users` SET `password` = :password WHERE `username` = :username";
        
        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}