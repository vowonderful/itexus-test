<?php

namespace Models;

use Base\Database;
use PDO;

class MainModel {

    public function getLastUsers(int $count): bool|array
    {
        /* $db = new Database(); */ 
        $_DB = $GLOBALS['database'];

        $sql = "SELECT `user_id`, `username`, `name` FROM `users` GROUP BY `user_id` ORDER BY `date_reg` DESC LIMIT :count";
        $stmt = $_DB->PDO()->prepare($sql);
        $stmt->bindParam(":count",$count, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}