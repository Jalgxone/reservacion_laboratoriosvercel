<?php
class Rol extends Model
{
    protected $table = 'roles';

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id_rol");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_rol = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
