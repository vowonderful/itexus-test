<?php

namespace Base;

use Controllers\RedirectController;

/**
 * Main Class.
 * Easy router -- parses a request for a controller and an action.
 */
class App {

    public static function run(): void
    {
        General::session();

        // I understand that it's bad. But initialization of the PDO
        // and connection to the database occurs
        // only 1 time during the entire launch of the application.
        // TODO: We need to find a more beautiful solution...
        $GLOBALS['database'] = new Database();

        new \Router\Routes;

        if ( General::isAuth() && !self::isExistAccount(General::getID() )) {
            unset($_SESSION);
            General::redirect('login', '303');
        }

    }

    private static function isExistAccount(int $userID): bool
    {
        $_DB = $GLOBALS['database'];

        $sql = "SELECT `user_id` FROM `users` WHERE `user_id` = :userID LIMIT 1";

        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":userID", $userID, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

}