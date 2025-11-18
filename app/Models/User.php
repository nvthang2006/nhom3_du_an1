<?php

namespace App\Models;

// require_once __DIR__ . '/BaseModel.php';
class User extends BaseModel
{
    protected $table = 'users';
    public function findByEmail($email)
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1", ['email' => $email]);
    }
    public function find($id)
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE user_id = :id", ['id' => $id]);
    }
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (full_name,email,password,phone) 
        VALUES (:name,:email,:password,:phone)";
        $this->execute($sql, ['name' => $data['full_name'], 'email' => $data['email'], 'password' => $data['password'], 'phone' => $data['phone']]);
        return $this->db->lastInsertId();
    }
}
