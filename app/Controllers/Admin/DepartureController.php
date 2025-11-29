<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\TourDeparture;
use App\Models\TourService;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Tour;

class DepartureController extends AdminBaseController
{

    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    // Hiển thị trang điều hành
    public function manage()
    {
        $id = $_GET['id'] ?? 0;

        $depModel = new TourDeparture();
        $departure = $depModel->find($id);

        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành.";
            $this->redirect('?act=admin-tours');
        }

        $tourModel = new Tour();
        $tour = $tourModel->find($departure['tour_id']);

        // Lấy danh sách HDV (user có role='hdv')
        $userModel = new User();
        $hdvs = $userModel->fetchAll("SELECT * FROM users WHERE role = 'hdv'");

        // Lấy dịch vụ đã đặt
        $srvModel = new TourService();
        $services = $srvModel->getByDepartureId($id);

        // Lấy danh sách NCC để gợi ý
        $supModel = new Supplier();
        $suppliers = $supModel->all();

        $this->view('admin/departures/manage', [
            'departure' => $departure,
            'tour' => $tour,
            'hdvs' => $hdvs,
            'services' => $services,
            'suppliers' => $suppliers
        ]);
    }

    // Lưu thông tin điều hành (Giờ, HDV, Xe...)
    public function updateOperational()
    {
        $id = $_POST['departure_id'];
        $data = [
            'start_time' => $_POST['start_time'],
            'gathering_point' => $_POST['gathering_point'],
            'hdv_id' => !empty($_POST['hdv_id']) ? $_POST['hdv_id'] : null,
            'driver_info' => $_POST['driver_info'],
            'logistics_info' => $_POST['logistics_info'],
            'id' => $id
        ];

        (new TourDeparture())->updateOperationalInfo($data);

        $_SESSION['flash'] = "Đã cập nhật thông tin điều hành.";
        $this->redirect("?act=admin-departures-manage&id=$id");
    }

    // Thêm dịch vụ
    public function addService()
    {
        $depId = $_POST['departure_id'];

        $data = [
            'departure_id' => $depId,
            'service_type' => $_POST['service_type'],
            'provider_name' => $_POST['provider_name'],
            'details' => $_POST['details'],
            'quantity' => $_POST['quantity'],
            'total_cost' => $_POST['total_cost'],
            'status' => 'Đã đặt'
        ];

        (new TourService())->add($data);

        $_SESSION['flash'] = "Đã thêm dịch vụ thành công.";
        $this->redirect("?act=admin-departures-manage&id=$depId");
    }

    // Xóa dịch vụ
    public function deleteService()
    {
        $id = $_POST['service_id'];
        $depId = $_POST['departure_id'];

        (new TourService())->delete($id);

        $_SESSION['flash'] = "Đã xóa dịch vụ.";
        $this->redirect("?act=admin-departures-manage&id=$depId");
    }
}
