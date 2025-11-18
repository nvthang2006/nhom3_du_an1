<?php

namespace App\Core;

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user']);
    }
    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['user'] ?? null;
    }
    public static function login(array $user)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($user['password']);
        $_SESSION['user'] = $user;
    }
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
    }
    public static function isRole($role)
    {
        $u = self::user();
        return $u && isset($u['role']) && $u['role'] === $role;
    }
    public static function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?act=login");
            exit();
        }
    }

    public static function requireRole($roles = [])
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?act=login");
            exit();
        }
        if (!in_array($_SESSION['user']['role'], $roles)) {
            echo "403 Forbidden";
            exit();
        }
    }
}
