<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\HdvProfile;
use App\Models\User;

class HdvController extends AdminBaseController
{

    public function index()
    {
        Auth::requireRole(['admin']);
        $model = new HdvProfile();
        $hdvs = $model->getAllHdvs();
        $this->view('admin/hdv/index', ['hdvs' => $hdvs]);
    }

    public function edit()
    {
        Auth::requireRole(['admin']);
        $userId = $_GET['id'] ?? 0;
        $model = new HdvProfile();
        $hdv = $model->getDetail($userId);

        if (!$hdv) {
            $_SESSION['flash'] = "Không tìm thấy HDV";
            $this->redirect('?act=admin-hdv');
        }

        $this->view('admin/hdv/edit', ['hdv' => $hdv]);
    }

    public function update()
    {
        Auth::requireRole(['admin']);
        $userId = $_POST['user_id'];

        // 1. Upload ảnh (Giữ nguyên logic cũ)
        $avatarPath = $_POST['old_avatar'] ?? null;
        if (!empty($_FILES['avatar']['name'])) {
            $targetDir = "uploads/avatars/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetDir . $fileName)) {
                $avatarPath = $targetDir . $fileName;
            }
        }

        // 2. Lưu profile (THÊM dòng lấy classification)
        $model = new HdvProfile();
        $data = [
            'dob' => $_POST['date_of_birth'],
            'lang' => $_POST['languages'],
            'cert' => $_POST['certificate'],
            'exp' => $_POST['experience_years'],
            'health' => $_POST['health_status'],
            'class'  => $_POST['classification'] ?? 'Nội địa', // Dòng mới thêm
            'avt' => $avatarPath
        ];
        $model->saveProfile($userId, $data);

        $_SESSION['flash'] = "Cập nhật hồ sơ HDV thành công!";
        $this->redirect('?act=admin-hdv-edit&id=' . $userId);
    }
}
