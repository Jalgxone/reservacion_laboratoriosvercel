<?php
require_once __DIR__ . '/../config/database.php';

class Validator
{
    // Reglas soportadas: required, int, datetime, email, minlen, maxlen, unique:table,field
    public static function validate(array $data, array $rules)
    {
        $errors = [];
        $db = Database::getConnection();

        foreach ($rules as $field => $r) {
            $value = $data[$field] ?? null;
            $parts = explode('|', $r);
            foreach ($parts as $part) {
                if ($part === 'required') {
                    if ($value === null || $value === '') {
                        $errors[$field][] = 'El campo ' . $field . ' es obligatorio.';
                    }
                } elseif ($part === 'int') {
                    if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field][] = 'El campo ' . $field . ' debe ser numérico.';
                    }
                } elseif ($part === 'datetime') {
                    if ($value !== null && $value !== '') {
                        $d = date_create($value);
                        if (!$d) $errors[$field][] = 'El campo ' . $field . ' debe ser fecha y hora válida.';
                    }
                } elseif ($part === 'email') {
                    if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = 'El campo ' . $field . ' debe ser un email válido.';
                    }
                } elseif (strpos($part, 'minlen:') === 0) {
                    $min = (int)substr($part, 7);
                    if (strlen((string)$value) < $min) $errors[$field][] = "El campo $field debe tener al menos $min caracteres.";
                } elseif (strpos($part, 'maxlen:') === 0) {
                    $max = (int)substr($part, 7);
                    if (strlen((string)$value) > $max) $errors[$field][] = "El campo $field debe tener como máximo $max caracteres.";
                } elseif (strpos($part, 'unique:') === 0) {
                    // formato unique:table,field
                    $u = substr($part, 7);
                    [$table, $col] = array_map('trim', explode(',', $u));
                    if ($table && $col && $value !== null && $value !== '') {
                        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$col} = :v";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['v' => $value]);
                        $row = $stmt->fetch();
                        if ($row && $row['cnt'] > 0) {
                            $errors[$field][] = "El valor de $field ya existe en la base de datos.";
                        }
                    }
                }
            }
        }

        return $errors;
    }
}
