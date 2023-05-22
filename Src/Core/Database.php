<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;
            try {
                self::$instance = new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        return self::$instance;
    }
}
