<?php
// app/Models/CustomerInBooking.php
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
}
