<?php

namespace App\Models;

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

    // --- CẬP NHẬT HÀM NÀY ---
    public function create($data)
    {
        // Mặc định role là customer nếu không truyền vào
        $role = $data['role'] ?? 'customer';
        $status = $data['status'] ?? '1';

        $sql = "INSERT INTO {$this->table} (full_name, email, password, phone, role, status) 
                VALUES (:name, :email, :password, :phone, :role, :status)";
        
        $this->execute($sql, [
            'name'     => $data['full_name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'],
            'role'     => $role,
            'status'   => $status
        ]);
        
        return $this->db->lastInsertId();
    }
}