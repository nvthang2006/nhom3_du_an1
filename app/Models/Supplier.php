<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    public function all()
    {
        // Lấy tất cả nhà cung cấp, sắp xếp theo tên
        return $this->fetchAll("SELECT * FROM {$this->table} ORDER BY supplier_name ASC");
    }

    // Lấy danh sách nhà cung cấp theo Tour ID
    public function getByTourId($tourId)
    {
        $sql = "SELECT s.* FROM {$this->table} s
                JOIN tour_suppliers ts ON s.supplier_id = ts.supplier_id
                WHERE ts.tour_id = :tid";
        return $this->fetchAll($sql, ['tid' => $tourId]);
    }

    // Thêm nhà cung cấp vào tour
    public function addToTour($tourId, $supplierId)
    {
        $sql = "INSERT IGNORE INTO tour_suppliers (tour_id, supplier_id) VALUES (:tid, :sid)";
        $this->execute($sql, ['tid' => $tourId, 'sid' => $supplierId]);
    }

    // Xóa nhà cung cấp khỏi tour
    public function removeFromTour($tourId, $supplierId)
    {
        $sql = "DELETE FROM tour_suppliers WHERE tour_id = :tid AND supplier_id = :sid";
        $this->execute($sql, ['tid' => $tourId, 'sid' => $supplierId]);
    }
}
