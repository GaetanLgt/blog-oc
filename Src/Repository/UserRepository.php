<?php

namespace App\Repository;

use App\Models\User;
use App\Core\Database;

class UserRepository
{
    public static function where($column, $value): ?User
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM user WHERE $column = :$column";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":$column", $value);
        $stmt->execute();
        if (!$stmt->rowCount()) {
            return null;
        }
        $user = $stmt->fetchObject(User::class);
        return $user;
    }

    public static function first(): ?User
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM user LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchObject(User::class);
        return $user;
    }
}
