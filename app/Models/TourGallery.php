<?php
namespace App\Models;

class TourGallery extends BaseModel
{
    protected $table = 'tour_galleries';
    protected $primaryKey = 'gallery_id';

    public function getByTourId($tourId)
    {
        return $this->fetchAll("SELECT * FROM {$this->table} WHERE tour_id = :id", ['id' => $tourId]);
    }

    public function add($tourId, $path)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, image_path) VALUES (:id, :path)";
        $this->execute($sql, ['id' => $tourId, 'path' => $path]);
    }

    public function delete($id)
    {
        $this->execute("DELETE FROM {$this->table} WHERE gallery_id = :id", ['id' => $id]);
    }
}