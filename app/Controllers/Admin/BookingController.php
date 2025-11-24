<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Tour;
use App\Models\CustomerInBooking;

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
    public function create()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        // Lấy danh sách tour đang hoạt động
        $tours = $db->query("SELECT tour_id, tour_name, price, max_people FROM tours WHERE status = 'Hoạt động'")->fetchAll();
        // Lấy danh sách user để làm người liên hệ
        // $customers = $db->query("SELECT user_id, full_name, phone, email FROM users WHERE role != 'admin'")->fetchAll();

        $this->view('admin/bookings/create', [
            'step'      => 1,
            'tours'     => $tours,
            // 'customers' => $customers
        ]);
    }

    public function prepare()
    {
        Auth::requireRole(['admin']);

        $tour_id      = $_POST['tour_id'];
        $people_count = (int)$_POST['total_people'];

        $mTour = new Tour();
        $tour = $mTour->find($tour_id);

        // 1. Logic tự động kiểm tra chỗ trống
        $booked = $mTour->getBookedSlots($tour_id);
        $max    = $tour['max_people'] ?? 20;
        $available = $max - $booked;

        if ($people_count > $available) {
            $_SESSION['error'] = "Tour này chỉ còn trống $available chỗ (Bạn nhập $people_count khách).";
            return $this->redirect('?act=admin-bookings-create');
        }

        // 2. Tính toán sơ bộ
        $totalPrice = $tour['price'] * $people_count;
        $admin_user = Auth::user();
        // Lấy thông tin khách hàng đại diện để hiển thị lại
        $contact_phone = $_POST['contact_phone'] ?? 'N/A';
        $customer = [
            'full_name' => htmlspecialchars($admin_user['full_name'] ?? 'Admin'),
            'phone'     => $contact_phone,
            'email'     => htmlspecialchars($admin_user['email'] ?? 'N/A')
        ];
        // 3. Chuyển sang View Bước 2
        $this->view('admin/bookings/create', [
            'step'       => 2,
            'tour'       => $tour,
            'customer'   => $customer,
            // Truyền ID admin làm created_by và SĐT liên hệ sang bước 2
            'preData'    => array_merge($_POST, [
                'contact_phone' => $contact_phone
            ]),
            'totalPrice' => $totalPrice
        ]);
    }

    public function store()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        // Nhận dữ liệu từ form
        $tour_id      = $_POST['tour_id'];
        
        // --- [SỬA ĐOẠN NÀY] ---
        // CŨ (Gây lỗi): $created_by = $_POST['created_by']; 
        // MỚI (Sửa thành): Lấy ID từ session của Admin đang đăng nhập
        $user         = Auth::user();
        $created_by   = $user['user_id']; 
        // ----------------------

        $start_date   = $_POST['start_date'];
        $total_people = $_POST['total_people'];
        $total_price  = $_POST['total_price'];
        $note         = $_POST['note'];
        $passengers   = $_POST['passengers'] ?? [];
        $contact_phone = $_POST['contact_phone'] ?? null;

        try {
            $db->beginTransaction();

            // Các đoạn code bên dưới giữ nguyên...
            $sql = "INSERT INTO bookings (tour_id, created_by, total_people, total_price, start_date, status, note) 
                    VALUES (:tid, :uid, :ppl, :price, :date, 'Chờ xác nhận', :note)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'tid'   => $tour_id,
                'uid'   => $created_by, // Biến này giờ đã có giá trị từ Auth::user()
                'ppl'   => $total_people,
                'price' => $total_price,
                'date'  => $start_date,
                'note'  => $note
            ]);
            $bookingId = $db->lastInsertId();

            // 2. Lưu chi tiết từng khách (Đoàn hoặc Lẻ)
            $cusModel = new CustomerInBooking();
            foreach ($passengers as $index => $p) {
                $phone_to_save = ($index == 0) ? $contact_phone : ($p['phone'] ?? null);

                $cusModel->add([
                    'booking_id'      => $bookingId,
                    'full_name'       => $p['full_name'],
                    'gender'          => $p['gender'],
                    'dob'             => !empty($p['dob']) ? $p['dob'] : null,
                    'phone'           => $phone_to_save, // <-- SĐT liên hệ được lưu ở đây
                    'passport_number' => $p['passport_number'],
                    'note'            => $p['note']
                ]);
            }

            $db->commit();
            $_SESSION['flash'] = "Đặt tour thành công! Booking #$bookingId đang chờ xác nhận.";
            $this->redirect('?act=admin-bookings');
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            $this->redirect('?act=admin-bookings-create');
        }
    }
}
