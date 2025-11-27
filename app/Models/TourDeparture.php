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
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tid 
                AND start_date >= CURDATE() 
                AND status = 'Mở bán'
                ORDER BY start_date ASC";
        return $this->fetchAll($sql, ['tid' => $tourId]);
    }

    // Cập nhật số lượng chỗ đã đặt
    public function updateBookedCount($departureId)
    {
        // Đếm tổng khách từ bảng bookings (trừ đơn hủy)
        $sql = "UPDATE {$this->table} 
                SET booked_count = (
                    SELECT COALESCE(SUM(total_people), 0) 
                    FROM bookings 
                    WHERE departure_id = :did AND status != 'Hủy'
                )
                WHERE departure_id = :did";
        $this->execute($sql, ['did' => $departureId]);
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
}
