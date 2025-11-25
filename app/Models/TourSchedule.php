<?php

namespace App\Models;

class TourSchedule extends BaseModel
{
    protected $table = 'tour_schedule';
    protected $primaryKey = 'schedule_id';

    public function add($data)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, day_number, description, location, image) 
                VALUES (:tour_id, :day_number, :description, :location, :image)";
        $this->execute($sql, $data);
    }
    public function getByTourId($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :id ORDER BY day_number ASC";
        return $this->fetchAll($sql, ['id' => $tourId]);
    }
    public function update($id, $data)
    {
        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = :{$key}";
        }
        $setSql = implode(', ', $sets);

        $sql = "UPDATE {$this->table} SET {$setSql} WHERE {$this->primaryKey} = :id";

        // Thêm ID vào mảng dữ liệu để bind param
        $data['id'] = $id;

        return $this->execute($sql, $data);
    }

    // THÊM: Hàm xóa nhiều lịch trình theo mảng ID (Dùng để xóa các dòng bị loại bỏ)
    public function deleteByIds(array $ids)
    {
        if (empty($ids)) return;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} IN ({$placeholders})";
        
        $this->execute($sql, $ids);
    }
}
