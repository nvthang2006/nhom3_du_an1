<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Models\Tour;
use App\Models\TourSchedule;

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
        $tours = $m->all(); // lấy danh sách tour
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
            'policy'        => $_POST['policy'],
            'status'        => $_POST['status'],
            'created_by'    => Auth::user()['user_id']
        ];

        // 1. Tạo Tour và lấy ID vừa tạo
        $newTourId = $m->create($data);

        // 2. Xử lý lưu Lịch trình (Nếu có)
        if (isset($_POST['schedules']) && is_array($_POST['schedules'])) {
            $schModel = new TourSchedule();

            foreach ($_POST['schedules'] as $index => $item) {
                $imagePath = null;

                // Xử lý upload ảnh cho từng ngày
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

                // Lưu vào DB
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

        // 1. Lấy thông tin Tour
        $m = new Tour();
        $tour = $m->find($id);

        // 2. Lấy thông tin Lịch trình
        $scheduleModel = new TourSchedule();
        $schedules = $scheduleModel->getByTourId($id);

        // 3. Truyền cả 2 biến sang View
        $this->view('admin/tours/detail', [
            'tour' => $tour,
            'schedules' => $schedules
        ]);
    }

    // --- SỬA HÀM EDIT ---
    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        // 1. Lấy thông tin Tour
        $m = new Tour();
        $tour = $m->find($id);

        if (!$tour) {
            die("Tour không tồn tại"); // Hoặc redirect và báo lỗi
        }

        // 2. Lấy thông tin Lịch trình cũ
        $schModel = new TourSchedule();
        $schedules = $schModel->getByTourId($id);

        $this->view('admin/tours/edit', [
            'tour' => $tour,
            'schedules' => $schedules // Truyền lịch trình sang view edit
        ]);
    }

    // --- SỬA HÀM UPDATE ---
    // --- SỬA HÀM UPDATE ---
    public function update()
    {
        // 1. Kiểm tra quyền hạn
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }

        $tourId = $_POST['id'];
        $m = new Tour();

        // 2. KHAI BÁO BIẾN $data (Đây là phần bạn đang thiếu)
        $data = [
            'tour_name'     => $_POST['tour_name'],
            'tour_type'     => $_POST['tour_type'],
            'description'   => $_POST['description'],
            'price'         => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'policy'        => $_POST['policy'],
            'status'        => $_POST['status'],
        ];

        // 3. Cập nhật bảng tours chính
        $m->update($tourId, $data);

        // --- XỬ LÝ LỊCH TRÌNH ---
        $schModel = new TourSchedule();

        // Lấy danh sách lịch trình CŨ đang có trong DB
        $currentDbSchedules = $schModel->getByTourId($tourId);

        // Lưu ý: Trong schema.sql, khóa chính là 'schedule_id', không phải 'id'
        $currentDbIds = array_column($currentDbSchedules, 'schedule_id');

        $submittedIds = [];

        if (isset($_POST['schedules']) && is_array($_POST['schedules'])) {
            foreach ($_POST['schedules'] as $index => $item) {
                $scheduleId = $item['id'] ?? null; // ID lấy từ input hidden

                // -- Xử lý upload ảnh --
                $imagePath = $item['old_image'] ?? null;

                $fileInputName = "schedules_image_" . $index;
                if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
                    $uploadDir = 'uploads/schedules/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    // Đổi tên file để tránh trùng
                    $fileName = time() . '_' . $tourId . '_' . $index . '_' . basename($_FILES[$fileInputName]['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    }
                }

                // Dữ liệu để lưu
                $scheduleData = [
                    'day_number'  => $item['day_number'],
                    'location'    => $item['location'],
                    'description' => $item['description'],
                    'image'       => $imagePath
                ];

                // -- Quyết định: INSERT hay UPDATE --
                // Kiểm tra xem ID gửi lên có tồn tại trong DB của tour này không
                if ($scheduleId && in_array($scheduleId, $currentDbIds)) {
                    // UPDATE
                    $schModel->update($scheduleId, $scheduleData);
                    $submittedIds[] = $scheduleId; // Đánh dấu là đã xử lý
                } else {
                    // INSERT
                    $scheduleData['tour_id'] = $tourId;
                    $schModel->add($scheduleData);
                }
            }
        }

        // 4. Xử lý XÓA (Các dòng có trong DB nhưng không được submit lại)
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
}
