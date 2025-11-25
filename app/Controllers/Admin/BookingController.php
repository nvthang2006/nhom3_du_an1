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
        $sql = "SELECT b.*, t.tour_name, 
                (SELECT phone FROM customers_in_booking WHERE booking_id = b.booking_id LIMIT 1) as contact_phone
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                ORDER BY b.created_at DESC";
        $rows = $db->query($sql)->fetchAll();
        $this->view('admin/bookings/index', ['bookings' => $rows]);
    }
    public function create()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        $tours = $db->query("SELECT tour_id, tour_name, price, max_people FROM tours WHERE status = 'Hoạt động'")->fetchAll();

        $this->view('admin/bookings/create', [
            'step'      => 1,
            'tours'     => $tours,
        ]);
    }

    public function prepare()
    {
        Auth::requireRole(['admin']);

        $tour_id      = $_POST['tour_id'];
        $people_count = (int)$_POST['total_people'];

        $mTour = new Tour();
        $tour = $mTour->find($tour_id);

        $booked = $mTour->getBookedSlots($tour_id);
        $max    = $tour['max_people'] ?? 20;
        $available = $max - $booked;

        if ($people_count > $available) {
            $_SESSION['error'] = "Tour này chỉ còn trống $available chỗ (Bạn nhập $people_count khách).";
            return $this->redirect('?act=admin-bookings-create');
        }

        $totalPrice = $tour['price'] * $people_count;
        $admin_user = Auth::user();
        // Lấy thông tin khách hàng đại diện để hiển thị lại
        $contact_phone = $_POST['contact_phone'] ?? 'N/A';
        $customer = [
            'full_name' => htmlspecialchars($admin_user['full_name'] ?? 'Admin'),
            'phone'     => $contact_phone,
            'email'     => htmlspecialchars($admin_user['email'] ?? 'N/A')
        ];
        $this->view('admin/bookings/create', [
            'step'       => 2,
            'tour'       => $tour,
            'customer'   => $customer,
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

        $tour_id      = $_POST['tour_id'];


        $user         = Auth::user();
        $created_by   = $user['user_id'];
        $start_date   = $_POST['start_date'];
        $total_people = $_POST['total_people'];
        $total_price  = $_POST['total_price'];
        $note         = $_POST['note'];
        $passengers   = $_POST['passengers'] ?? [];
        $contact_phone = $_POST['contact_phone'] ?? null;

        try {
            $db->beginTransaction();
            $sql = "INSERT INTO bookings (tour_id, created_by, total_people, total_price, start_date, status, note) 
                    VALUES (:tid, :uid, :ppl, :price, :date, 'Chờ xác nhận', :note)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                'tid'   => $tour_id,
                'uid'   => $created_by, 
                'ppl'   => $total_people,
                'price' => $total_price,
                'date'  => $start_date,
                'note'  => $note
            ]);
            $bookingId = $db->lastInsertId();

            $cusModel = new CustomerInBooking();
            foreach ($passengers as $index => $p) {
                $phone_to_save = ($index == 0) ? $contact_phone : ($p['phone'] ?? null);

                $cusModel->add([
                    'booking_id'      => $bookingId,
                    'full_name'       => $p['full_name'],
                    'gender'          => $p['gender'],
                    'dob'             => !empty($p['dob']) ? $p['dob'] : null,
                    'phone'           => $phone_to_save, 
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

    public function edit()
    {
        Auth::requireRole(['admin']);
        $id = $_GET['id'] ?? 0;
        $db = Database::getConnection();

        $sql = "SELECT b.*, t.tour_name 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                WHERE b.booking_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            $_SESSION['error'] = "Không tìm thấy Booking #$id";
            return $this->redirect('?act=admin-bookings');
        }

        $mCus = new CustomerInBooking();
        $customers = $mCus->getByBookingId($id);

        $this->view('admin/bookings/edit', [
            'booking' => $booking,
            'customers' => $customers
        ]);
    }

    public function update()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        $booking_id = $_POST['booking_id'];
        $status     = $_POST['status'];
        $passengers = $_POST['customers'] ?? [];

        try {
            $db->beginTransaction();
            $sqlBooking = "UPDATE bookings SET status = :st WHERE booking_id = :id";
            $stmt = $db->prepare($sqlBooking);
            $stmt->execute(['st' => $status, 'id' => $booking_id]);

            foreach ($passengers as $cusId => $data) {
                $sqlUpdate = "UPDATE customers_in_booking SET 
                              full_name = :fn, 
                              gender = :gd, 
                              dob = :dob, 
                              phone = :ph, 
                              passport_number = :pp, 
                              note = :nt
                              WHERE customer_id = :cid AND booking_id = :bid";

                $stmtUp = $db->prepare($sqlUpdate);
                $stmtUp->execute([
                    'fn' => $data['full_name'],
                    'gd' => $data['gender'],
                    'dob' => !empty($data['dob']) ? $data['dob'] : null,
                    'ph' => $data['phone'],
                    'pp' => $data['passport_number'],
                    'nt' => $data['note'],
                    'cid' => $cusId,
                    'bid' => $booking_id
                ]);
            }

            $db->commit();
            $_SESSION['flash'] = "Cập nhật Booking #$booking_id thành công!";
            $this->redirect("?act=admin-bookings");

        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Lỗi cập nhật: " . $e->getMessage();
            $this->redirect("?act=admin-bookings-edit&id=$booking_id");
        }
    }
}
