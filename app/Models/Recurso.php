<?php
class Recurso extends Model
{
    // For now, treat recursos as categories mapping to categorias_equipo
    public function getAll()
    {
        // Calculate quantity based on inventory count
        $sql = "SELECT c.id_categoria, c.nombre_categoria, c.requiere_mantenimiento_mensual, c.observacion,
                (SELECT COUNT(*) FROM inventario i WHERE i.id_categoria = c.id_categoria) as cantidad
                FROM categorias_equipo c
                ORDER BY c.nombre_categoria";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT c.id_categoria, c.nombre_categoria, c.requiere_mantenimiento_mensual, c.observacion,
                (SELECT COUNT(*) FROM inventario i WHERE i.id_categoria = c.id_categoria) as cantidad
                FROM categorias_equipo c
                WHERE c.id_categoria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // Cantidad is calculated, so we don't insert it (or insert 0 if column exists but unused)
        $stmt = $this->db->prepare('INSERT INTO categorias_equipo (nombre_categoria, requiere_mantenimiento_mensual, observacion) VALUES (:nombre, :maint, :observacion)');
        $stmt->execute([
            'nombre' => $data['nombre_categoria'],
            'maint' => $data['requiere_mantenimiento_mensual'] ? 1 : 0,
            'observacion' => $data['observacion'] ?? ''
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE categorias_equipo SET nombre_categoria = :nombre, requiere_mantenimiento_mensual = :maint, observacion = :observacion WHERE id_categoria = :id');
        return $stmt->execute([
            'nombre' => $data['nombre_categoria'],
            'maint' => $data['requiere_mantenimiento_mensual'] ? 1 : 0,
            'observacion' => $data['observacion'] ?? '',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM categorias_equipo WHERE id_categoria = :id');
        return $stmt->execute(['id' => $id]);
    }
}
