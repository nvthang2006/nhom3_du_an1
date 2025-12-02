<?php
namespace App\Models;

class TourLog extends BaseModel
{
    protected $table = 'tour_logs';
    protected $primaryKey = 'log_id';

    public function getByAssignId($assignId)
    {
        return $this->fetchAll("SELECT * FROM {$this->table} WHERE assign_id = :id ORDER BY log_date DESC, created_at DESC", ['id' => $assignId]);
    }

    public function add($data)
    {
        $sql = "INSERT INTO {$this->table} (assign_id, log_date, description, issue, feedback, image) 
                VALUES (:assign_id, :log_date, :description, :issue, :feedback, :image)";
        // Xử lý giá trị mặc định
        $data['issue'] = $data['issue'] ?? '';
        $data['feedback'] = $data['feedback'] ?? '';
        $data['image'] = $data['image'] ?? null;
        $this->execute($sql, $data);
    }

    public function find($id)
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE log_id = :id", ['id' => $id]);
    }

    public function update($id, $data)
    {
        $imageSql = isset($data['image']) ? ", image = :image" : "";
        $sql = "UPDATE {$this->table} SET log_date = :log_date, description = :description, issue = :issue $imageSql WHERE log_id = :id";
        $data['id'] = $id;
        $this->execute($sql, $data);
    }

    public function delete($id)
    {
        $this->execute("DELETE FROM {$this->table} WHERE log_id = :id", ['id' => $id]);
    }
}