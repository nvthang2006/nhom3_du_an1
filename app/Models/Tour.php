<?php

namespace App\Models;

class Tour extends BaseModel
{
    protected $table = 'tours';
    public function all()
    {
        return $this->fetchAll("SELECT * FROM {$this->table} 
        ORDER BY created_at DESC");
    }
    public function find($id)
    {
        return $this->fetch("SELECT * FROM {$this->table} 
        WHERE tour_id = :id", ['id' => $id]);
    }
    public function create($d)
    {
        $sql = "INSERT INTO {$this->table} 
        (tour_name, image, tour_type, description, price, duration_days, max_people, policy, status, created_by)
        VALUES 
        (:tour_name, :image, :tour_type, :description, :price, :duration_days, :max_people, :policy, :status, :created_by)";

        $this->execute($sql, $d);
        return $this->db->lastInsertId();
    }

    // Hàm Update đầy đủ (Đã sửa lỗi SQL)
    public function update($id, $d)
    {
        $sql = "UPDATE {$this->table} SET 
                tour_name = :tour_name,
                image = :image,
                tour_type = :tour_type,
                description = :description,
                price = :price,
                duration_days = :duration_days,
                max_people = :max_people, 
                policy = :policy,
                status = :status
            WHERE tour_id = :id";

        $d['id'] = $id;
        $this->execute($sql, $d);
        return true;
    }
    public function delete($id)
    {
        $this->execute("DELETE FROM {$this->table} WHERE tour_id=:id", ['id' => $id]);
        return true;
    }

    public function countAll()
    {
        // Sử dụng hàm fetch() của BaseModel để lấy 1 dòng kết quả
        $result = $this->fetch("SELECT COUNT(*) as total FROM {$this->table}");
        return $result['total'] ?? 0;
    }

    public function getBookedSlots($tourId)
    {
        // Chỉ tính các đơn chưa hủy
        $sql = "SELECT SUM(total_people) as total FROM bookings WHERE tour_id = :id AND status != 'Hủy'";
        $result = $this->fetch($sql, ['id' => $tourId]);
        return (int)($result['total'] ?? 0);
    }
}
