<?php

namespace App\Controllers\Hdv;

use App\Controllers\Admin\AdminBaseController;
use App\Core\Auth;
use App\Models\TourAssignment;
use App\Models\TourLog;
use App\Models\CustomerInBooking;
use App\Models\TourSchedule;

class TourDetailController extends AdminBaseController
{
    public function index()
    {
        if (!Auth::check() || !Auth::isRole('hdv')) {
            $this->redirect('?act=login');
        }

        $assignId = $_GET['id'] ?? 0; // Đây thực chất là departure_id
        $mAssign = new TourAssignment();
        $assign = $mAssign->find($assignId);

        // Bảo mật: Kiểm tra xem HDV có đúng là người dẫn tour này không
        if (!$assign || $assign['hdv_id'] != Auth::user()['user_id']) {
            $_SESSION['flash'] = "Bạn không có quyền truy cập tour này.";
            $this->redirect('?act=hdv-dashboard');
        }

        $mLog = new TourLog();
        $logs = $mLog->getByAssignId($assignId);

        $customers = $mAssign->getCustomers($assignId);

        $mSchedule = new TourSchedule();
        $schedules = $mSchedule->getByTourId($assign['tour_id']);

        $this->view('hdv/tour_detail', [
            'assign' => $assign,
            'logs' => $logs,
            'customers' => $customers,
            'schedules' => $schedules
        ]);
    }

    public function storeLog()
    {
        Auth::requireRole(['hdv']);
        $assignId = $_POST['assign_id'];
        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/logs/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_" . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $fileName)) {
                $imagePath = $targetDir . $fileName;
            }
        }

        (new TourLog())->add([
            'assign_id' => $assignId,
            'log_date' => $_POST['log_date'],
            'description' => $_POST['description'],
            'issue' => $_POST['issue'] ?? '',
            'image' => $imagePath
        ]);

        $_SESSION['flash'] = "Đã lưu nhật ký thành công.";
        $this->redirect("?act=hdv-tour-detail&id=$assignId");
    }

    public function updateCustomer()
    {
        Auth::requireRole(['hdv']);
        $mCus = new CustomerInBooking();
        $type = $_POST['type'];
        $customerId = $_POST['customer_id'];
        $assignId = $_POST['assign_id'];

        if ($type == 'checkin') {
            $mCus->updateCheckin($customerId, $_POST['value']);
        } elseif ($type == 'note') {
            $mCus->updateNote($customerId, $_POST['value']);
        }
        $this->redirect("?act=hdv-tour-detail&id=" . $assignId);
    }
}
