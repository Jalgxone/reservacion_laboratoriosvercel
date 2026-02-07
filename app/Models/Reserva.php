<?php
class Reserva extends Model
{
    protected $table = 'reservas';

    public function __construct()
    {
        parent::__construct();
        require_once __DIR__ . '/../../core/Security.php';
    }

    public function getAll()
    {
        $sql = "SELECT r.*, u.nombre_completo as usuario_nombre, l.nombre as laboratorio_nombre, e.nombre_estado
                FROM {$this->table} r
                LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario
                LEFT JOIN laboratorios l ON r.id_laboratorio = l.id_laboratorio
                LEFT JOIN estados_reserva e ON r.id_estado = e.id_estado
                ORDER BY r.fecha_inicio DESC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            if (!empty($row['motivo_uso'])) {
                $row['motivo_uso'] = Security::decrypt($row['motivo_uso']);
            }
        }
        return $rows;
    }

    public function getSchedule($labId, $start, $end)
    {
        $sql = "SELECT r.*, u.nombre_completo as usuario_nombre, l.nombre as laboratorio_nombre, e.nombre_estado, e.id_estado
                FROM {$this->table} r
                LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario
                LEFT JOIN laboratorios l ON r.id_laboratorio = l.id_laboratorio
                LEFT JOIN estados_reserva e ON r.id_estado = e.id_estado
                WHERE r.id_laboratorio = :lab
                AND r.fecha_inicio < :end AND r.fecha_fin > :start
                ORDER BY r.fecha_inicio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lab' => $labId, 'start' => $start, 'end' => $end]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            if (!empty($row['motivo_uso'])) {
                $row['motivo_uso'] = Security::decrypt($row['motivo_uso']);
            }
        }
        return $rows;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_reserva = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row && !empty($row['motivo_uso'])) {
            $row['motivo_uso'] = Security::decrypt($row['motivo_uso']);
        }
        return $row;
    }

    // check overlap helper
    protected function hasOverlap($labId, $start, $end, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE id_laboratorio = :lab AND NOT (fecha_fin <= :start OR fecha_inicio >= :end)";
        if ($excludeId) {
            $sql .= " AND id_reserva != :exclude";
        }
        $stmt = $this->db->prepare($sql);
        $params = ['lab' => $labId, 'start' => $start, 'end' => $end];
        if ($excludeId) $params['exclude'] = $excludeId;
        $stmt->execute($params);
        $row = $stmt->fetch();
        return !empty($row) && $row['cnt'] > 0;
    }

    public function create($data)
    {
        // ensure required fields
        $lab = $data['id_laboratorio'];
        $start = $data['fecha_inicio'];
        $end = $data['fecha_fin'];
        if ($this->hasOverlap($lab, $start, $end)) {
            throw new Exception('Ya existe una reserva en ese intervalo para el laboratorio seleccionado.');
        }

        // determine id_estado: use provided, else try to pick an existing estado, else create defaults
        $estadoId = $data['id_estado'] ?? null;
        if (empty($estadoId)) {
            $row = $this->db->query('SELECT id_estado FROM estados_reserva LIMIT 1')->fetch();
            if ($row && !empty($row['id_estado'])) {
                $estadoId = $row['id_estado'];
            } else {
                // seed some sensible defaults
                $this->db->beginTransaction();
                try {
                    $ins = $this->db->prepare('INSERT INTO estados_reserva (nombre_estado) VALUES (:n)');
                    $ins->execute(['n' => 'Pendiente']);
                    $ins->execute(['n' => 'Confirmada']);
                    $ins->execute(['n' => 'Cancelada']);
                    $this->db->commit();
                    $estadoId = $this->db->lastInsertId();
                    // fetch first id (Pendiente) as default
                    $row2 = $this->db->query('SELECT id_estado FROM estados_reserva ORDER BY id_estado ASC LIMIT 1')->fetch();
                    if ($row2 && !empty($row2['id_estado'])) {
                        $estadoId = $row2['id_estado'];
                    }
                } catch (Exception $e) {
                    $this->db->rollBack();
                    throw $e;
                }
            }
        }

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (id_usuario, id_laboratorio, fecha_inicio, fecha_fin, id_estado, motivo_uso) VALUES (:usuario, :lab, :start, :end, :estado, :motivo)");
        $motivo = $data['motivo_uso'] ?? null;
        if ($motivo !== null && $motivo !== '') {
            $motivo = Security::encrypt($motivo);
        }
        $stmt->execute([
            'usuario' => $data['id_usuario'],
            'lab' => $lab,
            'start' => $start,
            'end' => $end,
            'estado' => $estadoId,
            'motivo' => $motivo,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $lab = $data['id_laboratorio'];
        $start = $data['fecha_inicio'];
        $end = $data['fecha_fin'];
        if ($this->hasOverlap($lab, $start, $end, $id)) {
            throw new Exception('La modificación genera solapamiento con otra reserva.');
        }

        // determine estado id similar to create
        $estadoId = $data['id_estado'] ?? null;
        if (empty($estadoId)) {
            $row = $this->db->query('SELECT id_estado FROM estados_reserva LIMIT 1')->fetch();
            if ($row && !empty($row['id_estado'])) {
                $estadoId = $row['id_estado'];
            } else {
                // no estados exist -> create defaults
                $this->db->beginTransaction();
                try {
                    $ins = $this->db->prepare('INSERT INTO estados_reserva (nombre_estado) VALUES (:n)');
                    $ins->execute(['n' => 'Pendiente']);
                    $ins->execute(['n' => 'Confirmada']);
                    $ins->execute(['n' => 'Cancelada']);
                    $this->db->commit();
                    $row2 = $this->db->query('SELECT id_estado FROM estados_reserva ORDER BY id_estado ASC LIMIT 1')->fetch();
                    if ($row2 && !empty($row2['id_estado'])) {
                        $estadoId = $row2['id_estado'];
                    }
                } catch (Exception $e) {
                    $this->db->rollBack();
                    throw $e;
                }
            }
        }

        $stmt = $this->db->prepare("UPDATE {$this->table} SET id_laboratorio = :lab, fecha_inicio = :start, fecha_fin = :end, id_estado = :estado, motivo_uso = :motivo WHERE id_reserva = :id");
        $motivo = $data['motivo_uso'] ?? null;
        if ($motivo !== null && $motivo !== '') {
            $motivo = Security::encrypt($motivo);
        }
        return $stmt->execute([
            'lab' => $lab,
            'start' => $start,
            'end' => $end,
            'estado' => $estadoId,
            'motivo' => $motivo,
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_reserva = :id");
        return $stmt->execute(['id' => $id]);
    }
}
