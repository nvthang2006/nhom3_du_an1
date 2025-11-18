<?php
namespace App\Controllers\Hdv;
use App\Controllers\BaseController; use App\Core\Auth;
class DashboardController extends BaseController {
    public function index(){ if (!Auth::check() || !Auth::isRole('hdv')) { header('Location: /index.php/login'); exit; } $this->view('hdv/dashboard'); }
}
