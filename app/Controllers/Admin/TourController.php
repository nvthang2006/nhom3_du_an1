<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\Supplier;
use App\Models\TourGallery; // Đã thêm use Model này

class TourController extends AdminBaseController
{
    public function index()
    {
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
        $m = new Tour();
        $tours = $m->all();
        $this->view('admin/tours/create', ['tours' => $tours]);
    }

    public function store()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }

        // 1. Xử lý Ảnh đại diện (Thumbnail)
        $thumbPath = null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/tours/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_thumb_" . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $fileName)) {
                $thumbPath = $targetDir . $fileName;
            }
        }

        $m = new Tour();
        $data = [
            'tour_name'     => $_POST['tour_name'],
            'image'         => $thumbPath, // Đã có (Đúng)
            'tour_type'     => $_POST['tour_type'],
            'description'   => $_POST['description'],
            'price'         => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'max_people'    => $_POST['max_people'] ?? 20,
            'policy'        => $_POST['policy'],
            'status'        => $_POST['status'],
            'created_by'    => Auth::user()['user_id']
        ];

        $newTourId = $m->create($data);

        // 2. Xử lý Thư viện ảnh (Gallery)
        if (!empty($_FILES['gallery']['name'][0])) {
            $gModel = new TourGallery();
            $targetDir = "uploads/tours/gallery/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['gallery']['name'] as $key => $name) {
                if ($_FILES['gallery']['error'][$key] === 0) {
                    $fileName = time() . "_" . $key . "_" . basename($name);
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $targetDir . $fileName)) {
                        $gModel->add($newTourId, $targetDir . $fileName);
                    }
                }
            }
        }

        // 3. Xử lý Lịch trình
        if (isset($_POST['schedules']) && is_array($_POST['schedules'])) {
            $schModel = new TourSchedule();
            foreach ($_POST['schedules'] as $index => $item) {
                $imagePath = null;
                $fileInputName = "schedules_image_" . $index;
                if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
                    $uploadDir = 'uploads/schedules/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $fileName = time() . '_' . basename($_FILES[$fileInputName]['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    }
                }
                $schModel->add([
                    'tour_id'     => $newTourId,
                    'day_number'  => $item['day_number'],
                    'location'    => $item['location'],
                    'description' => $item['description'],
                    'image'       => $imagePath
                ]);
            }
        }

        $this->redirect('?act=admin-tours');
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $m = new Tour();
        $tour = $m->find($id);

        $scheduleModel = new TourSchedule();
        $schedules = $scheduleModel->getByTourId($id);

        $depModel = new \App\Models\TourDeparture();
        $upcomingDepartures = $depModel->getUpcomingByTour($id);
        $historyDepartures  = $depModel->getHistoryByTour($id);

        $supModel = new Supplier();
        $suppliers = $supModel->getByTourId($id);

        $allSuppliers = $supModel->all();

        $this->view('admin/tours/detail', [
            'tour' => $tour,
            'schedules' => $schedules,
            'upcomingDepartures' => $upcomingDepartures,
            'historyDepartures' => $historyDepartures,
            'suppliers' => $suppliers,
            'allSuppliers' => $allSuppliers
        ]);
    }

    public function addSupplier()
    {
        Auth::requireRole(['admin']);
        $tourId = $_POST['tour_id'];
        $supplierId = $_POST['supplier_id'];

        $supModel = new Supplier();
        $supModel->addToTour($tourId, $supplierId);

        $_SESSION['flash'] = "Đã thêm nhà cung cấp vào tour.";
        $this->redirect("?act=admin-tours-detail&id=$tourId");
    }

    public function removeSupplier()
    {
        Auth::requireRole(['admin']);
        $tourId = $_POST['tour_id'];
        $supplierId = $_POST['supplier_id'];

        $supModel = new Supplier();
        $supModel->removeFromTour($tourId, $supplierId);

        $_SESSION['flash'] = "Đã gỡ nhà cung cấp khỏi tour.";
        $this->redirect("?act=admin-tours-detail&id=$tourId");
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        $m = new Tour();
        $tour = $m->find($id);

        if (!$tour) {
            die("Tour không tồn tại");
        }

        $schModel = new TourSchedule();
        $schedules = $schModel->getByTourId($id);

        $gModel = new TourGallery();
        $gallery = $gModel->getByTourId($id);

        $this->view('admin/tours/edit', [
            'tour' => $tour,
            'schedules' => $schedules,
            'gallery' => $gallery
        ]);
    }

    public function update()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: ' . BASE_URL . '?act=login');
            exit;
        }

        $tourId = $_POST['id'];
        $m = new Tour();

        // 1. Xử lý Ảnh đại diện (Thumbnail)
        $thumbPath = $_POST['old_image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/tours/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_thumb_" . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $fileName)) {
                $thumbPath = $targetDir . $fileName;
            }
        }

        $data = [
            'tour_name'     => $_POST['tour_name'],
            'image'         => $thumbPath, // [ĐÃ SỬA] Thêm dòng này để khớp với Model Update
            'tour_type'     => $_POST['tour_type'],
            'description'   => $_POST['description'],
            'price'         => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'max_people'    => $_POST['max_people'] ?? 20,
            'policy'        => $_POST['policy'],
            'status'        => $_POST['status'],
        ];

        $m->update($tourId, $data);

        // 2. Xử lý Thư viện ảnh (Gallery)
        if (!empty($_FILES['gallery']['name'][0])) {
            $gModel = new TourGallery();
            $targetDir = "uploads/tours/gallery/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['gallery']['name'] as $key => $name) {
                if ($_FILES['gallery']['error'][$key] === 0) {
                    $fileName = time() . "_" . $key . "_" . basename($name);
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $targetDir . $fileName)) {
                        $gModel->add($tourId, $targetDir . $fileName);
                    }
                }
            }
        }

        // 3. Xử lý Lịch trình
        $schModel = new TourSchedule();
        $currentDbSchedules = $schModel->getByTourId($tourId);
        $currentDbIds = array_column($currentDbSchedules, 'schedule_id');
        $submittedIds = [];

        if (isset($_POST['schedules']) && is_array($_POST['schedules'])) {
            foreach ($_POST['schedules'] as $index => $item) {
                $scheduleId = $item['id'] ?? null;
                $imagePath = $item['old_image'] ?? null;

                $fileInputName = "schedules_image_" . $index;
                if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
                    $uploadDir = 'uploads/schedules/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $fileName = time() . '_' . $tourId . '_' . $index . '_' . basename($_FILES[$fileInputName]['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    }
                }

                $scheduleData = [
                    'day_number'  => $item['day_number'],
                    'location'    => $item['location'],
                    'description' => $item['description'],
                    'image'       => $imagePath
                ];

                if ($scheduleId && in_array($scheduleId, $currentDbIds)) {
                    $schModel->update($scheduleId, $scheduleData);
                    $submittedIds[] = $scheduleId;
                } else {
                    $scheduleData['tour_id'] = $tourId;
                    $schModel->add($scheduleData);
                }
            }
        }

        $idsToDelete = array_diff($currentDbIds, $submittedIds);

        if (!empty($idsToDelete)) {
            $schModel->deleteByIds($idsToDelete);
        }

        $this->redirect('?act=admin-tours');
    }

    public function deleteGalleryImage()
    {
        Auth::requireRole(['admin']);
        $id = $_GET['id'];
        $tourId = $_GET['tour_id'];
        (new TourGallery())->delete($id);
        $_SESSION['flash'] = "Đã xóa ảnh.";
        $this->redirect("?act=admin-tours-edit&id=$tourId");
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
        $this->redirect('?act=admin-tours');
    }

    public function storeDeparture()
    {
        Auth::requireRole(['admin']);

        $tourId = $_POST['tour_id'];
        $startDate = $_POST['start_date'];
        $duration = (int)$_POST['duration_days'];

        $endDate = date('Y-m-d', strtotime($startDate . " + $duration days"));

        $data = [
            'tour_id' => $tourId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $_POST['price'],
            'max_people' => $_POST['max_people']
        ];

        (new \App\Models\TourDeparture())->create($data);

        $_SESSION['flash'] = "Đã thêm lịch khởi hành ngày " . date('d/m', strtotime($startDate));
        $this->redirect("?act=admin-tours-detail&id=$tourId");
    }

    public function deleteDeparture()
    {
        Auth::requireRole(['admin']);
        $id = $_POST['departure_id'];
        $tourId = $_POST['tour_id'];

        (new \App\Models\TourDeparture())->delete($id);

        $_SESSION['flash'] = "Đã xóa lịch khởi hành.";
        $this->redirect("?act=admin-tours-detail&id=$tourId");
    }
}