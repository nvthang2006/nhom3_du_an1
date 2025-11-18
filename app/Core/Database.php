<?php
namespace App\Core;
use PDO;
use PDOException;

class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $host = '127.0.0.1';
            $db   = 'tourmanagement';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                file_put_contents(__DIR__ . '/../../storage/logs.txt', $e->getMessage() . PHP_EOL, FILE_APPEND);
                throw $e;
            }
        }
        return self::$pdo;
    }
}
