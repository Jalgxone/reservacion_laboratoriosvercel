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
        // verificar email único
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE email = :e");
        $stmt->execute(['e' => $data['email']]);
        $r = $stmt->fetch();
        if ($r && $r['cnt'] > 0) {
            throw new Exception('El email ya está registrado.');
        }

        require_once __DIR__ . '/../../core/Security.php';
        $hash = Security::hashPassword($data['password']);

        $sql = "INSERT INTO {$this->table} (nombre_completo, email, password_hash, id_rol) VALUES (:nombre, :email, :pass, :rol)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre_completo'],
            'email' => $data['email'],
            'pass' => $hash,
            'rol' => $data['id_rol'] ?? 1,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        // Verificar email único si se está actualizando
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

        if (isset($data['nombre_completo'])) {
            $fields[] = "nombre_completo = :nombre";
            $params['nombre'] = $data['nombre_completo'];
        }

        if (!empty($data['password'])) {
            require_once __DIR__ . '/../../core/Security.php';
            $fields[] = "password_hash = :pass";
            $params['pass'] = Security::hashPassword($data['password']);
        }

        if (empty($fields)) {
            return true; // Nada que actualizar
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
}
