<?php

namespace App\Models;

class TourDeparture extends BaseModel
{
    protected $table = 'tour_departures';
    protected $primaryKey = 'departure_id';

    public function find($id)
    {
        // Lấy thông tin chi tiết 1 lịch khởi hành theo ID
        $sql = "SELECT * FROM {$this->table} WHERE departure_id = :id";
        return $this->fetch($sql, ['id' => $id]);
    }
    // Lấy danh sách lịch khởi hành sắp tới của 1 tour
    public function getUpcomingByTour($tourId)
    {
        // Lấy lịch có ngày khởi hành >= hôm nay VÀ chưa hoàn tất/hủy
        $sql = "SELECT * FROM {$this->table} 
            WHERE tour_id = :tid 
            AND start_date >= CURDATE() 
            AND status NOT IN ('Hoàn tất', 'Hủy')
            ORDER BY start_date ASC";
        return $this->fetchAll($sql, ['tid' => $tourId]);
    }
    public function getHistoryByTour($tourId)
    {
        // Lấy lịch có ngày khởi hành < hôm nay HOẶC trạng thái là Hoàn tất/Hủy
        $sql = "SELECT * FROM {$this->table} 
            WHERE tour_id = :tid 
            AND (start_date < CURDATE() OR status IN ('Hoàn tất', 'Hủy'))
            ORDER BY start_date DESC"; // Mới nhất lên đầu
        return $this->fetchAll($sql, ['tid' => $tourId]);
    }

    // Cập nhật số lượng chỗ đã đặt
    public function updateBookedCount($departureId)
    {
        // SỬA LỖI HY093: Dùng 2 tên tham số khác nhau cho 2 vị trí placeholder
        $sql = "UPDATE {$this->table} 
                SET booked_count = (
                    SELECT COALESCE(SUM(total_people), 0) 
                    FROM bookings 
                    WHERE departure_id = :did_sub AND status != 'Hủy'
                )
                WHERE departure_id = :did_main";

        // Truyền giá trị vào cả 2 tham số
        $this->execute($sql, [
            'did_sub'  => $departureId,
            'did_main' => $departureId
        ]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, start_date, end_date, price, max_people, status) 
            VALUES (:tour_id, :start_date, :end_date, :price, :max_people, 'Mở bán')";
        $this->execute($sql, $data);
    }

    public function delete($id)
    {
        $this->execute("DELETE FROM {$this->table} WHERE departure_id = :id", ['id' => $id]);
    }

    public function updateOperationalInfo($data)
    {
        $sql = "UPDATE {$this->table} SET 
                start_time = :start_time,
                gathering_point = :gathering_point,
                hdv_id = :hdv_id,
                driver_info = :driver_info,
                logistics_info = :logistics_info
                WHERE departure_id = :id";
        $this->execute($sql, $data);
    }

    public function getDeparturesWithCompletedBookings($tourId)
    {
        // Chỉ lấy những lịch khởi hành có ít nhất 1 booking trạng thái 'Hoàn tất'
        $sql = "SELECT DISTINCT td.* FROM {$this->table} td
                JOIN bookings b ON td.departure_id = b.departure_id
                WHERE td.tour_id = :tid 
                AND b.status = 'Hoàn tất'
                ORDER BY td.start_date DESC";
        return $this->fetchAll($sql, ['tid' => $tourId]);
    }

    public function getHistoryByHdv($hdvId)
    {
        $sql = "SELECT d.*, t.tour_name 
                FROM {$this->table} d
                JOIN tours t ON d.tour_id = t.tour_id
                WHERE d.hdv_id = :hid 
                ORDER BY d.start_date DESC";
        return $this->fetchAll($sql, ['hid' => $hdvId]);
    }
}
