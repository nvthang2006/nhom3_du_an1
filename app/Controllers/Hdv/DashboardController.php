<?php
namespace App\Controllers\Hdv;

use App\Controllers\Admin\AdminBaseController; 
use App\Core\Auth;
use App\Models\TourAssignment;

class DashboardController extends AdminBaseController 
{
    public function index()
    {
        if (!Auth::check() || !Auth::isRole('hdv')) {
            header('Location: /index.php/login');
            exit;
        }

        $hdvId = Auth::user()['user_id'];
        $mAssign = new TourAssignment();
        $schedules = $mAssign->getScheduleByHdv($hdvId);

        $this->view('hdv/dashboard', [
            'pageTitle'    => 'Dashboard HDV',       
            'pageSubtitle' => 'Xin chào, đây là lịch làm việc của bạn',
            'schedules'    => $schedules
        ]);
    }
}