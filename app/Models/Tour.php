<?php

namespace App\Models;

class Tour extends BaseModel
{
    protected $table = 'tours';
    public function all()
    {
        return $this->fetchAll("SELECT * FROM {$this->table} ORDER BY created_at DESC");
    }
    public function find($id)
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE tour_id = :id", ['id' => $id]);
    }
    public function create($d)
    {
        $sql = "INSERT INTO {$this->table} (tour_name,tour_type,description,price,duration_days,policy,created_by) VALUES (:name,:type,:desc,:price,:days,:policy,:created_by)";
        $this->execute($sql, $d);
        return $this->db->lastInsertId();
    }
    public function update($id, $d)
    {
        $sql = "UPDATE {$this->table} SET tour_name=:name,tour_type=:type,description=:desc,price=:price,duration_days=:days,policy=:policy WHERE tour_id=:id";
        $d['id'] = $id;
        $this->execute($sql, $d);
        return true;
    }
    public function delete($id)
    {
        $this->execute("DELETE FROM {$this->table} WHERE tour_id=:id", ['id' => $id]);
        return true;
    }
}
