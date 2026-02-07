<?php
class Incidencia extends Model
{
    protected $table = 'incidencias';

    public function getAll()
    {
        $sql = "SELECT i.*, e.codigo_serial as equipo_serial, u.nombre_completo as usuario FROM {$this->table} i LEFT JOIN inventario e ON i.id_equipo = e.id_equipo LEFT JOIN usuarios u ON i.id_usuario_reporta = u.id_usuario ORDER BY i.fecha_reporte DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_incidencia = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (id_equipo, id_usuario_reporta, descripcion_problema, resuelto, nivel_gravedad) VALUES (:equipo, :usuario, :desc, :resuelto, :gravedad)");
        $stmt->execute([
            'equipo' => $data['id_equipo'],
            'usuario' => $data['id_usuario_reporta'],
            'desc' => $data['descripcion_problema'],
            'resuelto' => !empty($data['resuelto']) ? 1 : 0,
            'gravedad' => $data['nivel_gravedad'] ?? 'media',
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET id_equipo = :equipo, descripcion_problema = :desc, resuelto = :resuelto, nivel_gravedad = :gravedad WHERE id_incidencia = :id");
        return $stmt->execute([
            'equipo' => $data['id_equipo'],
            'desc' => $data['descripcion_problema'],
            'resuelto' => !empty($data['resuelto']) ? 1 : 0,
            'gravedad' => $data['nivel_gravedad'] ?? 'media',
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_incidencia = :id");
        return $stmt->execute(['id' => $id]);
    }
}
