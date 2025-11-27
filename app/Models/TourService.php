<?php
namespace App\Models;

class TourService extends BaseModel {
    protected $table = 'tour_services';

    public function getByAssignId($assignId) {
        return $this->fetchAll("SELECT * FROM {$this->table} WHERE assign_id = :aid", ['aid' => $assignId]);
    }

    public function add($data) {
        $sql = "INSERT INTO {$this->table} (assign_id, service_type, provider_name, details, quantity, total_cost, status) 
                VALUES (:assign_id, :type, :provider, :details, :qty, :cost, :status)";
        $this->execute($sql, $data);
    }

    public function delete($id) {
        $this->execute("DELETE FROM {$this->table} WHERE service_id = :id", ['id' => $id]);
    }
}