<?php
namespace App\Models;

class TourAssignment extends BaseModel {
    protected $table = 'tour_assignments';

    // Lấy danh sách lịch khởi hành kèm tên Tour và tên HDV
    public function getAllAssignments() {
        $sql = "SELECT a.*, t.tour_name, u.full_name as hdv_name 
                FROM {$this->table} a
                JOIN tours t ON a.tour_id = t.tour_id
                LEFT JOIN users u ON a.hdv_id = u.user_id
                ORDER BY a.start_date DESC";
        return $this->fetchAll($sql);
    }

    public function find($id) {
        return $this->fetch("SELECT * FROM {$this->table} WHERE assign_id = :id", ['id' => $id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (tour_id, start_date, start_time, end_date, gathering_point, note) 
                VALUES (:tid, :sdate, :stime, :edate, :point, :note)";
        $this->execute($sql, $data);
        return $this->db->lastInsertId();
    }

    public function updateStaff($id, $hdvId, $transport, $logistics) {
        $sql = "UPDATE {$this->table} SET hdv_id = :hdv, transport_info = :trans, logistics_staff = :log 
                WHERE assign_id = :id";
        $this->execute($sql, ['hdv' => $hdvId, 'trans' => $transport, 'log' => $logistics, 'id' => $id]);
    }
}