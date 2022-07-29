<?php

namespace Models;

use Base\Database;
use PDO;

class AccountModel {

    /**
     * Retrieves user information
     * @param int $userID ID of the user whose information we are requesting
     * @return bool|array Returns all available information
     *                    (or false if the information for the specified ID could not be found)
     */
    public function getUserByID(int $userID, array $additionally = []): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $addSql = '';
        if ( !empty($additionally) ) {
            $addSql = implode('`, `', $additionally);
            $addSql = ', `' . $addSql . '` ';
        }

        $sql = "SELECT `user_id`, `username`, `email`, `name`, `country`, `date_birth`, `status`, `role`" .
            $addSql . " FROM `users` WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function userBlock(int $userID): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sql = "UPDATE `users` SET `status` = 'blocked' WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function userUnblock(int $userID): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sql = "UPDATE `users` SET `status` = 'active' WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserStatus(int $userID): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sql = "SELECT `status` FROM `users` WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function accountUpdate(int $userID, array $data): bool|array
    {
        /* $db = new Database(); */
        $_DB = $GLOBALS['database'];

        $sqlAdded = '';
        if ( !empty($data['password'] ) ) {
            $sqlAdded = ', `password` = :password';
        };

        $sql = "UPDATE `users` SET `email` = :email, `country` = :country, `date_birth` = :birthday, `name` = :name" . $sqlAdded . " WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":country", $data['country'], PDO::PARAM_STR);
        $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
        if ( !empty($data['birthday']) )
            $stmt->bindParam(":birthday", $data['birthday'], PDO::PARAM_STR);
        else
            $stmt->bindParam(":birthday", $data['birthday'], PDO::PARAM_NULL);
        if ( !empty($data['password'] ) )
            $stmt->bindParam(":password", $data['password'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUpdateParams(int $userID, bool $includePassword): bool|array
    {
        /* $db = new Database(); */
        $_DB = $GLOBALS['database'];

        $sqlAdded = '';
        if ( $includePassword ) {
            $sqlAdded = ', `password`';
        };

        $sql = "SELECT `email`, `country`, `date_birth`, `name`" . $sqlAdded . " FROM `users` WHERE `user_id` = :userID";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}