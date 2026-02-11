<?php
class Laboratorio extends Model
{
    protected $table = 'laboratorios';

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_laboratorio = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombre, ubicacion, capacidad_personas, esta_activo) VALUES (:nombre, :ubicacion, :capacidad, :esta)");
        $stmt->execute([
            'nombre' => $data['nombre'] ?? null,
            'ubicacion' => $data['ubicacion'] ?? null,
            'capacidad' => $data['capacidad_personas'] ?? 0,
            'esta' => !empty($data['esta_activo']) ? 1 : 0,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nombre = :nombre, ubicacion = :ubicacion, capacidad_personas = :capacidad, esta_activo = :esta WHERE id_laboratorio = :id");
        return $stmt->execute([
            'nombre' => $data['nombre'] ?? null,
            'ubicacion' => $data['ubicacion'] ?? null,
            'capacidad' => $data['capacidad_personas'] ?? 0,
            'esta' => !empty($data['esta_activo']) ? 1 : 0,
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET esta_activo = 0 WHERE id_laboratorio = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleStatus($id)
    {
        $sql = "UPDATE {$this->table} SET esta_activo = 1 - esta_activo WHERE id_laboratorio = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
