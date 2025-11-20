<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    // Giữ nguyên biến tĩnh để đảm bảo Singleton (chỉ 1 kết nối)
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                // Sử dụng các hằng số từ file Env.php
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
            } catch (PDOException $e) {
                // Xử lý lỗi
                die("Lỗi kết nối CSDL: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
