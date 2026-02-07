<?php
class Inventario extends Model
{
    public function getAll()
    {
        $sql = "SELECT i.id_equipo, i.codigo_serial, i.id_laboratorio, l.nombre as laboratorio, i.id_categoria, c.nombre_categoria, i.marca_modelo, i.estado_operativo
                FROM inventario i
                LEFT JOIN laboratorios l ON i.id_laboratorio = l.id_laboratorio
                LEFT JOIN categorias_equipo c ON i.id_categoria = c.id_categoria
                ORDER BY i.id_equipo DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT i.id_equipo, i.codigo_serial, i.id_laboratorio, l.nombre as laboratorio, i.id_categoria, c.nombre_categoria, i.marca_modelo, i.estado_operativo
                FROM inventario i
                LEFT JOIN laboratorios l ON i.id_laboratorio = l.id_laboratorio
                LEFT JOIN categorias_equipo c ON i.id_categoria = c.id_categoria
                WHERE i.id_equipo = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        // verificar unicidad de codigo_serial
        $stmt = $this->db->prepare('SELECT COUNT(*) as cnt FROM inventario WHERE codigo_serial = :s');
        $stmt->execute(['s' => $data['codigo_serial']]);
        $r = $stmt->fetch();
        if ($r && $r['cnt'] > 0) {
            throw new Exception('El código serial ya existe en el inventario.');
        }

        $sql = "INSERT INTO inventario (codigo_serial, id_laboratorio, id_categoria, marca_modelo, estado_operativo) VALUES (:codigo_serial, :id_laboratorio, :id_categoria, :marca_modelo, :estado_operativo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'codigo_serial' => $data['codigo_serial'],
            'id_laboratorio' => $data['id_laboratorio'],
            'id_categoria' => $data['id_categoria'],
            'marca_modelo' => $data['marca_modelo'],
            'estado_operativo' => $data['estado_operativo'],
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        // verificar unicidad excluyendo el registro actual
        $stmt = $this->db->prepare('SELECT COUNT(*) as cnt FROM inventario WHERE codigo_serial = :s AND id_equipo != :id');
        $stmt->execute(['s' => $data['codigo_serial'], 'id' => $id]);
        $r = $stmt->fetch();
        if ($r && $r['cnt'] > 0) {
            throw new Exception('El código serial ya existe en otro registro del inventario.');
        }

        $sql = "UPDATE inventario SET codigo_serial = :codigo_serial, id_laboratorio = :id_laboratorio, id_categoria = :id_categoria, marca_modelo = :marca_modelo, estado_operativo = :estado_operativo WHERE id_equipo = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'codigo_serial' => $data['codigo_serial'],
            'id_laboratorio' => $data['id_laboratorio'],
            'id_categoria' => $data['id_categoria'],
            'marca_modelo' => $data['marca_modelo'],
            'estado_operativo' => $data['estado_operativo'],
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM inventario WHERE id_equipo = :id');
        return $stmt->execute(['id' => $id]);
    }
}
