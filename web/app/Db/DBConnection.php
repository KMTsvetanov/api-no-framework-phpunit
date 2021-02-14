<?php


namespace App\Db;

use PDO;

class DBConnection
{

    protected static $instance;

    protected static $host = 'mysql';
    protected static $db = 'DBname';
    protected static $user = 'root';
    protected static $pass = 'root';
    protected static $charset = 'utf8';
    protected static $port = '3306';


    public static function getInstance()
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . self::$host .";dbname=" . self::$db . ";charset=" . self::$charset . ";port=" . self::$port,
                    self::$user,
                    self::$pass
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $error) {
                echo 'Connection failed: ' . $error->getMessage();
            }
        }

        return self::$instance;
    }
}