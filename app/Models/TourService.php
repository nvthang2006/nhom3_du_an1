<?php
namespace App\Models;

class TourService extends BaseModel {
    protected $table = 'tour_services';
    protected $primaryKey = 'service_id';

    // Lấy danh sách dịch vụ theo ID Lịch khởi hành
    public function getByDepartureId($depId) {
        return $this->fetchAll("SELECT * FROM {$this->table} WHERE departure_id = :did", ['did' => $depId]);
    }

    // Thêm dịch vụ mới
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (departure_id, service_type, provider_name, details, quantity, total_cost, status) 
                VALUES (:departure_id, :service_type, :provider_name, :details, :quantity, :total_cost, :status)";
        $this->execute($sql, $data);
    }

    // Xóa dịch vụ
    public function delete($id) {
        $this->execute("DELETE FROM {$this->table} WHERE service_id = :id", ['id' => $id]);
    }
}