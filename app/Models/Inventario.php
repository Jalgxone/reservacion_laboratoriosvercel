<?php
class Inventario extends Model
{
    public function getAll()
    {
        $sql = "SELECT i.id_equipo, i.codigo_serial, i.id_laboratorio, l.nombre as laboratorio, i.id_categoria, c.nombre_categoria, i.marca_modelo, i.estado_operativo, i.esta_activo
                FROM inventario i
                LEFT JOIN laboratorios l ON i.id_laboratorio = l.id_laboratorio
                LEFT JOIN categorias_equipo c ON i.id_categoria = c.id_categoria
                ORDER BY i.id_equipo DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT i.id_equipo, i.codigo_serial, i.id_laboratorio, l.nombre as laboratorio, i.id_categoria, c.nombre_categoria, i.marca_modelo, i.estado_operativo, i.esta_activo
                FROM inventario i
                LEFT JOIN laboratorios l ON i.id_laboratorio = l.id_laboratorio
                LEFT JOIN categorias_equipo c ON i.id_categoria = c.id_categoria
                WHERE i.id_equipo = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function generateNextID($id_categoria)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM inventario WHERE id_categoria = :cat");
        $stmt->execute(['cat' => $id_categoria]);
        $row = $stmt->fetch();
        $nextNum = ($row['count'] ?? 0) + 1;
        

        return sprintf("EQU-%02d-%04d", $id_categoria, $nextNum);
    }

    public function create($data)
    {
        $codigo_serial = $this->generateNextID($data['id_categoria']);

        $sql = "INSERT INTO inventario (codigo_serial, id_laboratorio, id_categoria, marca_modelo, estado_operativo) VALUES (:codigo_serial, :id_laboratorio, :id_categoria, :marca_modelo, :estado_operativo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'codigo_serial' => $codigo_serial,
            'id_laboratorio' => $data['id_laboratorio'],
            'id_categoria' => $data['id_categoria'],
            'marca_modelo' => $data['marca_modelo'],
            'estado_operativo' => $data['estado_operativo'],
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE inventario 
                SET id_laboratorio = :id_laboratorio, 
                    id_categoria = :id_categoria, 
                    marca_modelo = :marca_modelo, 
                    estado_operativo = :estado_operativo,
                    esta_activo = :esta_activo
                WHERE id_equipo = :id";
        $stmt = $this->db->prepare($sql);
        
        $esta_activo = $data['esta_activo'] ?? 1;
        if (isset($data['estado_operativo']) && $data['estado_operativo'] === 'Baja') {
            $esta_activo = 0;
        }

        return $stmt->execute([
            'id_laboratorio' => $data['id_laboratorio'],
            'id_categoria' => $data['id_categoria'],
            'marca_modelo' => $data['marca_modelo'],
            'estado_operativo' => $data['estado_operativo'],
            'esta_activo' => $esta_activo,
            'id' => $id,
        ]);
    }

    public function hasActiveReservations($id)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM reservas r
                JOIN inventario i ON r.id_laboratorio = i.id_laboratorio
                WHERE i.id_equipo = :id 
                  AND r.fecha_fin >= NOW() 
                  AND r.id_estado IN (SELECT id_estado FROM estados_reserva WHERE nombre_estado NOT IN ('Cancelada', 'Finalizada'))";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return ($row['count'] > 0);
    }

    public function hasPendingIncidents($id)
    {
        $sql = "SELECT COUNT(*) as count FROM incidencias WHERE id_equipo = :id AND resuelto = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return ($row['count'] > 0);
    }

    public function delete($id)
    {
        if ($this->hasActiveReservations($id)) {
            throw new Exception("No se puede eliminar el equipo porque tiene reservas activas o futuras.");
        }
        if ($this->hasPendingIncidents($id)) {
            throw new Exception("No se puede eliminar el equipo porque tiene incidencias pendientes.");
        }

        $stmt = $this->db->prepare("UPDATE inventario SET esta_activo = 0 WHERE id_equipo = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleStatus($id)
    {
        $item = $this->getById($id);
        if (!$item) throw new Exception("Equipo no encontrado.");

        $targetStatus = 1 - $item['esta_activo'];

        if ($targetStatus == 1 && $this->hasPendingIncidents($id)) {
            throw new Exception("No se puede activar el equipo porque tiene incidencias sin resolver.");
        }

        if ($targetStatus == 0 && $this->hasActiveReservations($id)) {
            throw new Exception("No se puede desactivar el equipo porque el laboratorio tiene reservas activas.");
        }

        $sql = "UPDATE inventario SET esta_activo = :status WHERE id_equipo = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $targetStatus, 'id' => $id]);
    }
}
