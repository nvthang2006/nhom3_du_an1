<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;

class BookingController extends AdminBaseController
{
    public function index()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $db = Database::getConnection();
        $rows = $db->query('SELECT * FROM bookings ORDER BY created_at DESC')->fetchAll();
        $this->view('admin/bookings/index', ['bookings' => $rows]);
    }
    public function store()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $db = Database::getConnection();
        $sql = "INSERT INTO bookings (tour_id,created_by,total_people,total_price,start_date,status,note) VALUES (:tour_id,:created_by,:total_people,:total_price,:start_date,:status,:note)";
        $db->prepare($sql)->execute(['tour_id' => $_POST['tour_id'], 'created_by' => Auth::user()['user_id'], 'total_people' => $_POST['total_people'], 'total_price' => $_POST['total_price'], 'start_date' => $_POST['start_date'], 'status' => 'Chờ xác nhận', 'note' => $_POST['note']]);
        $this->redirect('/index.php/admin/bookings');
    }
}
