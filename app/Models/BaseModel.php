<?php

namespace App\Models;

use App\Core\Database;

class BaseModel
{
    protected $db;
    protected $table = '';
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    protected function fetchAll($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    protected function fetch($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    protected function execute($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
