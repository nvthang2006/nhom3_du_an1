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
    public function create()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }
        $db = Database::getConnection();

        // Lấy danh sách Tour đang hoạt động để chọn
        $tours = $db->query("SELECT tour_id, tour_name, price FROM tours WHERE status = 'Hoạt động'")->fetchAll();

        // Lấy danh sách Khách hàng (User có role customer)
        // Nếu bạn muốn Admin đặt được cho cả user khác, có thể bỏ điều kiện WHERE role...
        $customers = $db->query("SELECT user_id, full_name, email, phone FROM users WHERE role != 'admin'")->fetchAll();

        $this->view('admin/bookings/create', [
            'tours' => $tours,
            'customers' => $customers
        ]);
    }

    // 2. Xử lý Lưu Booking
    public function store()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }

        $db = Database::getConnection();

        // Lấy dữ liệu từ Form
        $tour_id = $_POST['tour_id'];
        $customer_id = $_POST['customer_id']; // ID khách hàng được chọn
        $people = (int)$_POST['total_people'];
        $date = $_POST['start_date'];
        $note = $_POST['note'];
        $price_manual = $_POST['total_price']; // Giá admin chốt

        // --- [LOGIC CHECK CHỖ TRỐNG - GIẢ LẬP] ---
        // (Sau này bạn cần thêm cột max_people vào bảng tours để check thật)
        // Ví dụ: $tour = $db->query("SELECT * FROM tours WHERE tour_id=$tour_id")->fetch();
        // if ($people > $tour['max_people']) { ... báo lỗi ... }

        // Insert vào DB
        // Lưu ý: Trường created_by trong bảng bookings hiện tại nên lưu ID của KHÁCH HÀNG
        // để sau này khách đăng nhập vào xem được lịch sử của họ.
        $sql = "INSERT INTO bookings (tour_id, created_by, total_people, total_price, start_date, status, note) 
                VALUES (:tour_id, :customer_id, :total_people, :total_price, :start_date, :status, :note)";

        try {
            $db->prepare($sql)->execute([
                'tour_id' => $tour_id,
                'customer_id' => $customer_id, // Lưu người đặt là khách hàng
                'total_people' => $people,
                'total_price' => $price_manual,
                'start_date' => $date,
                'status' => 'Chờ xác nhận', // Mặc định mới tạo là Chờ
                'note' => $note
            ]);

            // Đặt flash message (nếu bạn đã làm chức năng này)
            $_SESSION['flash'] = "Tạo booking mới thành công!";
            $this->redirect('?act=admin-bookings');
        } catch (\PDOException $e) {
            // Xử lý lỗi nếu có
            die("Lỗi tạo booking: " . $e->getMessage());
        }
    }
}
