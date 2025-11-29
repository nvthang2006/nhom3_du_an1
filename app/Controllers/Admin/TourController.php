<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\Supplier;

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

        $m = new Tour();
        $data = [
            'tour_name'     => $_POST['tour_name'],
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

        $this->view('admin/tours/edit', [
            'tour' => $tour,
            'schedules' => $schedules
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

        $data = [
            'tour_name'     => $_POST['tour_name'],
            'tour_type'     => $_POST['tour_type'],
            'description'   => $_POST['description'],
            'price'         => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'max_people'    => $_POST['max_people'] ?? 20,
            'policy'        => $_POST['policy'],
            'status'        => $_POST['status'],
        ];

        $m->update($tourId, $data);

        $schModel = new TourSchedule();

        // Lấy danh sách lịch trình CŨ đang có trong DB
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
        $duration = (int)$_POST['duration_days']; // Lấy từ hidden field hoặc query lại tour

        // Tính ngày kết thúc
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

    // 3. Thêm hàm xóa lịch
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
