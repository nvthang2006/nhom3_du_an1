<?php
namespace App\Models;

class HdvProfile extends BaseModel {
    protected $table = 'hdv_profiles';
    protected $primaryKey = 'profile_id';

    // Lấy danh sách HDV kèm thông tin user
    public function getAllHdvs() {
        // THÊM: p.classification vào câu SELECT
        $sql = "SELECT u.user_id, u.full_name, u.email, u.phone, u.status, 
                       p.profile_id, p.languages, p.experience_years, p.rating, p.avatar, p.classification
                FROM users u 
                LEFT JOIN hdv_profiles p ON u.user_id = p.user_id 
                WHERE u.role = 'hdv' 
                ORDER BY u.full_name ASC";
        return $this->fetchAll($sql);
    }

    // Lấy chi tiết 1 HDV (giữ nguyên vì p.* đã lấy hết các cột)
    public function getDetail($userId) {
        $sql = "SELECT p.*, u.user_id, u.full_name, u.email, u.phone, u.status, u.role
                FROM users u 
                LEFT JOIN hdv_profiles p ON u.user_id = p.user_id 
                WHERE u.user_id = :uid";    
        return $this->fetch($sql, ['uid' => $userId]);
    }

    // Cập nhật hoặc Thêm mới profile
    public function saveProfile($userId, $data) {
        $exists = $this->fetch("SELECT profile_id FROM {$this->table} WHERE user_id = :uid", ['uid' => $userId]);

        $avatarSql = !empty($data['avt']) ? ", avatar = :avt" : "";
        
        // THÊM: classification vào câu UPDATE và INSERT
        if ($exists) {
            $sql = "UPDATE {$this->table} SET 
                    date_of_birth = :dob, languages = :lang, certificate = :cert, 
                    experience_years = :exp, health_status = :health, classification = :class
                    $avatarSql
                    WHERE user_id = :uid";
        } else {
            $sql = "INSERT INTO {$this->table} (user_id, date_of_birth, languages, certificate, experience_years, health_status, classification, avatar) 
                    VALUES (:uid, :dob, :lang, :cert, :exp, :health, :class, :avt)";
        }
        
        $params = [
            'uid' => $userId,
            'dob' => $data['dob'],
            'lang' => $data['lang'],
            'cert' => $data['cert'],
            'exp' => $data['exp'],
            'health' => $data['health'],
            'class' => $data['class'] // Tham số mới
        ];
        
        if (!$exists || !empty($data['avt'])) {
            $params['avt'] = $data['avt'];
        }

        $this->execute($sql, $params);
    }
}