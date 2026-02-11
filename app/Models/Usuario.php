<?php
class Usuario extends Model
{
    protected $table = 'usuarios';

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_usuario = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getStats($id)
    {
        $stats = [
            'total' => 0,
            'pendientes' => 0,
            'confirmadas' => 0,
            'canceladas' => 0,
            'proxima' => null
        ];

        $sql = "SELECT id_estado, COUNT(*) as cnt FROM reservas WHERE id_usuario = :id GROUP BY id_estado";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $stats['total'] += $row['cnt'];
            if ($row['id_estado'] == 1) $stats['pendientes'] = $row['cnt'];
            if ($row['id_estado'] == 2) $stats['confirmadas'] = $row['cnt'];
            if ($row['id_estado'] == 3) $stats['canceladas'] = $row['cnt'];
        }

        $sql = "SELECT r.*, l.nombre as laboratorio_nombre 
                FROM reservas r 
                JOIN laboratorios l ON r.id_laboratorio = l.id_laboratorio 
                WHERE r.id_usuario = :id AND r.fecha_inicio > NOW() AND r.id_estado != 3 
                ORDER BY r.fecha_inicio ASC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $stats['proxima'] = $stmt->fetch();

        return $stats;
    }

    public function authenticate($email, $password)
    {
        $user = $this->getByEmail($email);
        if ($user && !empty($user['password_hash'])) {
            require_once __DIR__ . '/../../core/Security.php';
            if (Security::verifyPassword($password, $user['password_hash'])) {
                return $user;
            }
        }
        return false;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE email = :e");
        $stmt->execute(['e' => $data['email']]);
        $r = $stmt->fetch();
        if ($r && $r['cnt'] > 0) {
            throw new Exception('El email ya está registrado.');
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE cedula_identidad = :c");
        $stmt->execute(['c' => $data['cedula_identidad'] ?? ($data['cedula'] ?? '')]);
        $r = $stmt->fetch();
        if ($r && $r['cnt'] > 0) {
            throw new Exception('La cédula ya está registrada.');
        }

        require_once __DIR__ . '/../../core/Security.php';
        $hash = Security::hashPassword($data['password']);

        $sql = "INSERT INTO {$this->table} (nombre_completo, apellido, cedula_identidad, telefono, email, password_hash, id_rol, estado) VALUES (:nombre, :apellido, :cedula, :telefono, :email, :pass, :rol, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre_completo'],
            'apellido' => $data['apellido'] ?? '',
            'cedula' => $data['cedula'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'email' => $data['email'],
            'pass' => $hash,
            'rol' => $data['id_rol'] ?? 1,
            'estado' => $data['estado'] ?? 'pendiente'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        if (!empty($data['email'])) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE email = :e AND id_usuario != :id");
            $stmt->execute(['e' => $data['email'], 'id' => $id]);
            $r = $stmt->fetch();
            if ($r && $r['cnt'] > 0) {
                throw new Exception('El email ya está en uso por otro usuario.');
            }
            $fields[] = "email = :email";
            $params['email'] = $data['email'];
        }

        if (!empty($data['cedula_identidad']) || !empty($data['cedula'])) {
            $cedulaValue = $data['cedula_identidad'] ?? $data['cedula'];
            $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE cedula_identidad = :c AND id_usuario != :id");
            $stmt->execute(['c' => $cedulaValue, 'id' => $id]);
            $r = $stmt->fetch();
            if ($r && $r['cnt'] > 0) {
                throw new Exception('La cédula ya está en uso por otro usuario.');
            }
        }

        if (isset($data['nombre_completo'])) {
            $fields[] = "nombre_completo = :nombre";
            $params['nombre'] = $data['nombre_completo'];
        }

        if (isset($data['apellido'])) {
            $fields[] = "apellido = :apellido";
            $params['apellido'] = $data['apellido'];
        }

        if (isset($data['cedula_identidad']) || isset($data['cedula'])) {
            $fields[] = "cedula_identidad = :cedula";
            $params['cedula'] = $data['cedula_identidad'] ?? ($data['cedula'] ?? '');
        }

        if (isset($data['telefono'])) {
            $fields[] = "telefono = :telefono";
            $params['telefono'] = $data['telefono'];
        }

        if (isset($data['id_rol'])) {
            $fields[] = "id_rol = :id_rol";
            $params['id_rol'] = $data['id_rol'];
        }

        if (isset($data['estado'])) {
            $fields[] = "estado = :estado";
            $params['estado'] = $data['estado'];
        }

        if (!empty($data['password'])) {
            require_once __DIR__ . '/../../core/Security.php';
            $fields[] = "password_hash = :pass";
            $params['pass'] = Security::hashPassword($data['password']);
        }

        if (empty($fields)) {
            return true;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function setResetToken($email, $token, $expires)
    {
        $sql = "UPDATE {$this->table} SET reset_token = :token, reset_expires = :expires WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'email' => $email
        ]);
    }

    public function getUserByToken($token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE reset_token = :token AND reset_expires > NOW() LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function clearResetToken($id)
    {
        $sql = "UPDATE {$this->table} SET reset_token = NULL, reset_expires = NULL WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getAll()
    {
        $sql = "SELECT u.*, r.nombre_rol FROM {$this->table} u LEFT JOIN roles r ON u.id_rol = r.id_rol ORDER BY u.id_usuario DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET estado = 'inactivo' WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }
}
