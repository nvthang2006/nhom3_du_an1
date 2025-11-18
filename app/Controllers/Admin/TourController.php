<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\Tour;

class TourController extends AdminBaseController
{
    public function index()
    {
        // if (!Auth::check() || !Auth::isRole('admin')) {
        //     header('Location: /index.php/login');
        //     exit;
        // }
        $m = new Tour();
        $tours = $m->all();
        $this->view('admin/tours/index', ['tours' => $tours]);
    }
    public function create()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $this->view('admin/tours/create');
    }
    public function store()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $m = new Tour();
        $data = ['tour_name' => $_POST['tour_name'], 'tour_type' => $_POST['tour_type'], 'description' => $_POST['description'], 'price' => $_POST['price'], 'duration_days' => $_POST['duration_days'], 'policy' => $_POST['policy'], 'created_by' => Auth::user()['user_id']];
        $m->create($data);
        $this->redirect('/index.php/admin/tours');
    }
    public function edit()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $id = $_GET['id'] ?? 0;
        $m = new Tour();
        $tour = $m->find((int)$id);
        $this->view('admin/tours/edit', ['tour' => $tour]);
    }
    public function update()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $id = $_POST['id'];
        $m = new Tour();
        $data = ['tour_name' => $_POST['tour_name'], 'tour_type' => $_POST['tour_type'], 'description' => $_POST['description'], 'price' => $_POST['price'], 'duration_days' => $_POST['duration_days'], 'policy' => $_POST['policy']];
        $m->update($id, $data);
        $this->redirect('/index.php/admin/tours');
    }
    public function delete()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $id = $_POST['id'];
        $m = new Tour();
        $m->delete($id);
        $this->redirect('/index.php/admin/tours');
    }
}
