<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\Supplier;

class SupplierController extends AdminBaseController
{
    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    // 1. Hiển thị danh sách
    public function index()
    {
        $model = new Supplier();
        // Hàm all() bạn đã thêm ở bước trước
        $suppliers = $model->all();
        $this->view('admin/suppliers/index', ['suppliers' => $suppliers]);
    }

    // 2. Form tạo mới
    public function create()
    {
        $this->view('admin/suppliers/save', ['title' => 'Thêm Nhà cung cấp']);
    }

    // 3. Lưu dữ liệu tạo mới
    public function store()
    {
        $data = [
            'supplier_name' => $_POST['supplier_name'],
            'service_type'  => $_POST['service_type'],
            'contact_phone' => $_POST['contact_phone'],
            'email'         => $_POST['email'],
            'address'       => $_POST['address']
        ];

        $model = new Supplier();
        // Lưu ý: Cần thêm hàm create() vào Model Supplier nếu chưa có (xem Bước bổ sung bên dưới)
        // Hoặc dùng hàm execute insert thủ công
        $sql = "INSERT INTO suppliers (supplier_name, service_type, contact_phone, email, address) 
                VALUES (:name, :type, :phone, :email, :address)";

        $model->execute($sql, [
            'name' => $data['supplier_name'],
            'type' => $data['service_type'],
            'phone' => $data['contact_phone'],
            'email' => $data['email'],
            'address' => $data['address']
        ]);

        $_SESSION['flash'] = "Đã thêm nhà cung cấp mới!";
        $this->redirect('?act=admin-suppliers');
    }

    // 4. Form sửa
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $model = new Supplier();
        $supplier = $model->fetch("SELECT * FROM suppliers WHERE supplier_id = :id", ['id' => $id]);

        if (!$supplier) {
            $_SESSION['error'] = "Không tìm thấy nhà cung cấp.";
            $this->redirect('?act=admin-suppliers');
        }

        $this->view('admin/suppliers/save', [
            'title' => 'Cập nhật Nhà cung cấp',
            'supplier' => $supplier
        ]);
    }

    // 5. Lưu cập nhật
    public function update()
    {
        $id = $_POST['supplier_id'];
        $sql = "UPDATE suppliers SET 
                supplier_name = :name, service_type = :type, 
                contact_phone = :phone, email = :email, address = :address 
                WHERE supplier_id = :id";

        (new Supplier())->execute($sql, [
            'name' => $_POST['supplier_name'],
            'type' => $_POST['service_type'],
            'phone' => $_POST['contact_phone'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'id' => $id
        ]);

        $_SESSION['flash'] = "Cập nhật thành công!";
        $this->redirect('?act=admin-suppliers');
    }

    // 6. Xóa
    public function delete()
    {
        $id = $_POST['id'];
        // Xóa liên kết trong bảng tour_suppliers trước (nếu có rằng buộc khóa ngoại)
        $model = new Supplier();
        $model->execute("DELETE FROM tour_suppliers WHERE supplier_id = :id", ['id' => $id]);
        $model->execute("DELETE FROM suppliers WHERE supplier_id = :id", ['id' => $id]);

        $_SESSION['flash'] = "Đã xóa nhà cung cấp.";
        $this->redirect('?act=admin-suppliers');
    }
}
