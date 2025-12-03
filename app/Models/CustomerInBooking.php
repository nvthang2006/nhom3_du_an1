<?php

namespace App\Models;

class CustomerInBooking extends BaseModel
{
    protected $table = 'customers_in_booking';

    // Hàm thêm mới một hành khách
    public function add($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (booking_id, full_name, gender, dob, phone, passport_number, note) 
                VALUES 
                (:booking_id, :full_name, :gender, :dob, :phone, :passport_number, :note)";

        return $this->execute($sql, $data);
    }

    // Hàm lấy danh sách hành khách theo booking_id (để hiển thị chi tiết sau này)
    public function getByBookingId($bookingId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE booking_id = :bid";
        return $this->fetchAll($sql, ['bid' => $bookingId]);
    }
    public function getPassengersByDeparture($departureId)
    {
        // Sửa lỗi: Thay b.contact_phone bằng subquery lấy sđt của khách đầu tiên trong booking
        $sql = "SELECT c.*, b.status as booking_status,
                (SELECT phone FROM customers_in_booking WHERE booking_id = b.booking_id ORDER BY customer_id ASC LIMIT 1) as booker_phone
                FROM {$this->table} c
                JOIN bookings b ON c.booking_id = b.booking_id
                WHERE b.departure_id = :did 
                AND b.status != 'Hủy'
                ORDER BY c.full_name ASC";

        return $this->fetchAll($sql, ['did' => $departureId]);
    }

    public function updateQuickInfo($customerId, $note)
    {
        $sql = "UPDATE {$this->table} SET note = :note WHERE customer_id = :id";
        return $this->execute($sql, ['note' => $note, 'id' => $customerId]);
    }
    public function updateCheckin($customerId, $status)
    {
        $sql = "UPDATE {$this->table} SET check_in = :s WHERE customer_id = :id";
        $this->execute($sql, ['s' => $status, 'id' => $customerId]);
    }

    public function updateNote($customerId, $note)
    {
        $sql = "UPDATE {$this->table} SET note = :n WHERE customer_id = :id";
        $this->execute($sql, ['n' => $note, 'id' => $customerId]);
    }
}
