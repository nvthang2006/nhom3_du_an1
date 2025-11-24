<?php
namespace App\Models;

class TourAssignment extends BaseModel {
    protected $table = 'tour_assignments';
    
    // Hàm lấy lịch trình theo ID của HDV
    public function getScheduleByHdv($hdvId) {
        // Kết nối bảng tour_assignments với bảng tours để lấy tên tour
        $sql = "SELECT ta.*, t.tour_name, t.duration_days, t.tour_type 
                FROM {$this->table} ta
                JOIN tours t ON ta.tour_id = t.tour_id
                WHERE ta.hdv_id = :hid
                ORDER BY ta.start_date DESC";
                
        return $this->fetchAll($sql, ['hid' => $hdvId]);
    }
}