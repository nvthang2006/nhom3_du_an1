<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\Tour; // Nhớ use Model Tour

class DashboardController extends AdminBaseController
{
    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    public function index()
    {
        $tourModel = new Tour();

        // 2. Lấy dữ liệu thống kê
        $stats = [
            'tours_count' => $tourModel->countAll(),
            // Bạn có thể thêm các chỉ số khác ở đây (ví dụ: số booking, doanh thu...)
            'today_bookings' => 0,
            'month_revenue' => 0
        ];

        // 3. Truyền dữ liệu sang View
        $this->view('admin/dashboard', [
            'stats' => $stats
        ]);
    }
}
