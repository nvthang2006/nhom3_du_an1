<?php
namespace App\Controllers\Hdv;

// Import AdminBaseController để dùng chung layout
use App\Controllers\Admin\AdminBaseController; 
use App\Core\Auth;
use App\Models\TourAssignment; // (Nếu bạn muốn hiển thị lịch trình ngay dashboard)

// Thay đổi: extends AdminBaseController
class DashboardController extends AdminBaseController 
{
    public function index()
    {
        // 1. Kiểm tra quyền HDV
        if (!Auth::check() || !Auth::isRole('hdv')) {
            header('Location: /index.php/login');
            exit;
        }

        // 2. Lấy dữ liệu (Ví dụ: Lấy lịch trình sắp tới)
        $hdvId = Auth::user()['user_id'];
        $mAssign = new TourAssignment();
        // Giả sử bạn đã thêm hàm getScheduleByHdv vào model TourAssignment như hướng dẫn trước
        // Nếu chưa có thì tạm thời để mảng rỗng
        $schedules = method_exists($mAssign, 'getScheduleByHdv') ? $mAssign->getScheduleByHdv($hdvId) : [];

        // 3. Gọi View (Sẽ tự động dùng admin/layout.php)
        $this->view('hdv/dashboard', [
            'pageTitle'    => 'Dashboard HDV',             // Tiêu đề hiển thị trên Layout
            'pageSubtitle' => 'Xin chào, đây là lịch làm việc của bạn',
            'schedules'    => $schedules
        ]);
    }
}