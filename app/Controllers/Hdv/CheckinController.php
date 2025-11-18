<?php
namespace App\Controllers\Hdv;
use App\Controllers\BaseController; use App\Core\Auth; use App\Models\CheckinRecord;
class CheckinController extends BaseController {
    public function list(){ if (!Auth::check() || !Auth::isRole('hdv')) { header('Location: /index.php/login'); exit; } $assignId = $_GET['assign_id'] ?? 0; $m = new CheckinRecord(); $records = $m->listByAssign((int)$assignId); $this->view('hdv/checkin/list',['records'=>$records]); }
    public function create(){ if (!Auth::check() || !Auth::isRole('hdv')) { header('Location: /index.php/login'); exit; } $m = new CheckinRecord(); $data = ['booking_id'=>$_POST['booking_id'] ?? null,'customer_id'=>$_POST['customer_id'] ?? null,'schedule_id'=>$_POST['schedule_id'],'assign_id'=>$_POST['assign_id'] ?? null,'hdv_id'=>Auth::user()['user_id'],'checkin_time'=>date('Y-m-d H:i:s'),'checkout_time'=>$_POST['checkout_time'] ?? null,'checked_count'=>$_POST['checked_count'] ?? 0,'status'=>$_POST['status'] ?? 'CheckedIn','note'=>$_POST['note'] ?? null]; $m->add($data); $this->redirect('/index.php/hdv/checkin'); }
}
