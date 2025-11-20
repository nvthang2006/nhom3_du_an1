<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    // ghi đè view để dùng layout admin
    protected function view($file, $data = [])
    {
        extract($data);
        // view nội dung
        $viewFile = __DIR__ . '/../../Views/' . $file . '.php'; // ví dụ 'admin/tours/index'
        // admin layout path
        $layout = __DIR__ . '/../../Views/admin/layout.php';
        if (!file_exists($viewFile)) {
            echo "View not found: $viewFile";
            return;
        }
        // admin layout sẽ require $viewFile
        require $layout;
    }
}
