<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\Tour;

class DashboardController extends AdminBaseController
{
    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    public function index()
    {
        $tourModel = new Tour();
        $stats = [
            'tours_count' => $tourModel->countAll(),
            'today_bookings' => 0,
            'month_revenue' => 0
        ];

        $this->view('admin/dashboard', [
            'stats' => $stats
        ]);
    }
}
