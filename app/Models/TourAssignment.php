<?php

namespace App\Models;

class TourAssignment extends BaseModel
{
    protected $table = 'tour_assignments';
    protected $primaryKey = 'departure_id'; // Thêm dòng này để chuẩn hóa

    // Lấy danh sách lịch khởi hành kèm tên Tour và tên HDV (Dành cho Admin)
    public function getAllAssignments()
    {
        return [];
    }

    public function find($id)
    {
        // $id ở đây chính là departure_id được truyền từ URL
        $sql = "SELECT d.*, d.departure_id as assign_id, t.tour_name, t.duration_days, t.tour_id 
                FROM tour_departures d
                JOIN tours t ON d.tour_id = t.tour_id
                WHERE d.departure_id = :id";
        return $this->fetch($sql, ['id' => $id]);
    }

    // --- MỚI: Lấy lịch trình của riêng HDV (Cho Dashboard HDV) ---
    public function getScheduleByHdv($hdvId)
    {
        // Lấy lịch từ bảng tour_departures
        // QUAN TRỌNG: Alias 'departure_id' thành 'assign_id' để khớp với View
        $sql = "SELECT d.*, d.departure_id as assign_id, t.tour_name, t.image
                FROM tour_departures d
                JOIN tours t ON d.tour_id = t.tour_id
                WHERE d.hdv_id = :uid
                ORDER BY d.start_date DESC";

        return $this->fetchAll($sql, ['uid' => $hdvId]);
    }

    // --- MỚI: Lấy danh sách khách của tour này (Cho chức năng Check-in) ---
    public function getCustomers($departureId)
    {
        // Lấy khách từ các Booking thuộc departure_id này
        // Chỉ lấy khách có trạng thái booking là 'Đã cọc' hoặc 'Hoàn tất'
        $sql = "SELECT c.*, b.booking_id, b.status as booking_status
                FROM customers_in_booking c
                JOIN bookings b ON c.booking_id = b.booking_id
                WHERE b.departure_id = :did 
                AND b.status IN ('Đã cọc', 'Hoàn tất')
                ORDER BY c.full_name ASC";

        return $this->fetchAll($sql, ['did' => $departureId]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, start_date, start_time, end_date, gathering_point, note) 
                VALUES (:tid, :sdate, :stime, :edate, :point, :note)";
        $this->execute($sql, $data);
        return $this->db->lastInsertId();
    }

    public function updateStaff($id, $hdvId, $transport, $logistics)
    {
        $sql = "UPDATE {$this->table} SET hdv_id = :hdv, transport_info = :trans, logistics_staff = :log 
                WHERE assign_id = :id";
        $this->execute($sql, ['hdv' => $hdvId, 'trans' => $transport, 'log' => $logistics, 'id' => $id]);
    }
}
