<?php
namespace App\Models;

class CheckinRecord extends BaseModel {
    protected $table = 'checkin_records';
    public function add($d){ $sql = "INSERT INTO {$this->table} (booking_id,customer_id,schedule_id,assign_id,hdv_id,checkin_time,checkout_time,checked_count,status,note) VALUES (:booking_id,:customer_id,:schedule_id,:assign_id,:hdv_id,:checkin_time,:checkout_time,:checked_count,:status,:note)"; $this->execute($sql,$d); return $this->db->lastInsertId(); }
    public function listByAssign($assignId){ return $this->fetchAll("SELECT * FROM {$this->table} WHERE assign_id = :a ORDER BY checkin_time", ['a'=>$assignId]); }
}
