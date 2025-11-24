<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;

class AuthController extends BaseController
{
    public function showLogin()
    {
        $this->view('login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $pass  = $_POST['password'] ?? '';

        $m = new User();
        $u = $m->findByEmail($email);

        // Không tìm thấy user
        if (!$u) {
            return $this->view('login', ['error' => 'Email không tồn tại']);
        }

        // Sai mật khẩu
        if (!password_verify($pass, $u['password'])) {
            return $this->view('login', ['error' => 'Mật khẩu không đúng']);
        }

        // Đăng nhập thành công
        if ($u['role'] === 'admin') {
            header('Location: ' . BASE_URL . '?act=admin-dashboard');
            exit;
        }

        if ($u['role'] === 'hdv') {
            header('Location: ' . BASE_URL . '?act=hdv-dashboard');
            exit;
        }

        if ($u['role'] === 'customer') {
            header('Location: ' . BASE_URL); // Hoặc trang chủ khách hàng
            exit;
        }

        // Nếu các role khác => không có quyền
        return $this->view('login', ['error' => 'Tài khoản không có quyền truy cập']);
    }

    public function showRegister()
    {
        $this->view('register');
    }

    public function register()
    {
        $full_name = $_POST['full_name'] ?? '';
        $email     = $_POST['email'] ?? '';
        $phone     = $_POST['phone'] ?? '';
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['password_confirmation'] ?? '';

        // Check mật khẩu khớp
        if ($password !== $confirm) {
            return $this->view('register', ['error' => 'Mật khẩu không khớp']);
        }

        $m = new User();

        // Check email tồn tại
        if ($m->findByEmail($email)) {
            return $this->view('register', ['error' => 'Email đã tồn tại']);
        }

        // Tạo user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $m->create([
            'full_name' => $full_name,
            'email'     => $email,
            'phone'     => $phone,
            'password'  => $hashedPassword,
            'role'      => 'user' // mặc định
        ]);

        $_SESSION['success'] = 'Đăng ký thành công, hãy đăng nhập.';
        return header('Location: ' . BASE_URL . '?act=login');
    }

    public function logout()
    {
        Auth::logout();
        return header('Location: ' . BASE_URL . '?act=login');
    }
}
