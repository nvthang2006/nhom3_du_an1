<?php
namespace App\Models;

class TourExpense extends BaseModel {
    protected $table = 'tour_expenses';

    public function add($data) {
        $sql = "INSERT INTO {$this->table} (tour_id, title, amount, expense_date, note, created_by) 
                VALUES (:tour_id, :title, :amount, :expense_date, :note, :created_by)";
        $this->execute($sql, $data);
    }

    public function getByTourId($tourId) {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :id ORDER BY expense_date DESC";
        return $this->fetchAll($sql, ['id' => $tourId]);
    }
    
    public function delete($id) {
        $this->execute("DELETE FROM {$this->table} WHERE expense_id = :id", ['id' => $id]);
    }
}