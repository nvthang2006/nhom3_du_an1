<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/AdminBaseController.php';

use App\Controllers\BaseController;
use App\Core\Auth;
class DashboardController extends AdminBaseController
{
    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    public function index()
    {
        $this->view('admin/dashboard');
    }
}
