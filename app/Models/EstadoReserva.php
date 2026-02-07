<?php
class EstadoReserva extends Model
{
    protected $table = 'estados_reserva';

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id_estado ASC");
        return $stmt->fetchAll();
    }

    public function existsAny()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM {$this->table}");
        $row = $stmt->fetch();
        return ($row && $row['cnt'] > 0);
    }
}
