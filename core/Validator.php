<?php
require_once __DIR__ . '/../config/database.php';

class Validator
{

    public static function validate(array $data, array $rules, array $labels = [])
    {
        $errors = [];
        $db = Database::getConnection();
        $finalLabels = array_merge([
            'nombre_categoria' => 'categoría',
            'observacion' => 'observación',
            'requiere_mantenimiento_mensual' => 'mantenimiento mensual'
        ], $labels);

        foreach ($rules as $field => $r) {
            $value = $data[$field] ?? null;
            $parts = explode('|', $r);
            $displayName = $finalLabels[$field] ?? str_replace('_', ' ', $field);

            foreach ($parts as $part) {
                if ($part === 'required') {
                    if ($value === null || $value === '') {
                        $errors[$field][] = 'El campo ' . $displayName . ' es obligatorio.';
                    }
                } elseif ($part === 'int') {
                    if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field][] = 'El campo ' . $displayName . ' debe ser numérico.';
                    }
                } elseif ($part === 'datetime') {
                    if ($value !== null && $value !== '') {
                        $d = date_create($value);
                        if (!$d) $errors[$field][] = 'El campo ' . $displayName . ' debe ser fecha y hora válida.';
                    }
                } elseif ($part === 'email') {
                    if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = 'El campo ' . $displayName . ' debe ser un email válido.';
                    }
                } elseif ($part === 'common_email') {
                    if ($value !== null && $value !== '' && !preg_match('/@(gmail|outlook|hotmail|yahoo|icloud)\.com$/i', (string)$value)) {
                        $errors[$field][] = "El campo " . $displayName . " debe ser de un proveedor conocido (Gmail, Outlook, Yahoo, etc.).";
                    }
                } elseif ($part === 'alphanumeric_special') {
                    if ($value !== null && $value !== '' && !preg_match('/^[a-z0-9\s#,]*$/i', (string)$value)) {
                        $errors[$field][] = "El campo " . $displayName . " no permite signos de ningún tipo, ignorando solo el de numeral (#) y la coma (,).";
                    }
                } elseif (strpos($part, 'min_val:') === 0) {
                    $min = (int)substr($part, 8);
                    if ($value !== null && $value !== '' && (int)$value < $min) {
                        $labelSufix = ($field === 'capacidad') ? ' personas' : '';
                        $errors[$field][] = "El campo " . $displayName . " debe ser de al menos $min$labelSufix.";
                    }
                } elseif (strpos($part, 'max_val:') === 0) {
                    $max = (int)substr($part, 8);
                    if ($value !== null && $value !== '' && (int)$value > $max) {
                        $labelSufix = ($field === 'capacidad') ? ' personas' : '';
                        $errors[$field][] = "El campo " . $displayName . " no puede ser mayor a $max$labelSufix.";
                    }
                } elseif (strpos($part, 'minlen:') === 0) {
                    $min = (int)substr($part, 7);
                    if (strlen((string)$value) < $min) $errors[$field][] = "El campo " . $displayName . " debe tener al menos $min caracteres.";
                } elseif (strpos($part, 'maxlen:') === 0) {
                    $max = (int)substr($part, 7);
                    if (strlen((string)$value) > $max) $errors[$field][] = "El campo " . $displayName . " debe tener como máximo $max caracteres.";
                } elseif (strpos($part, 'regex:') === 0) {
                    $pattern = substr($part, 6);
                    if ($value !== null && $value !== '' && !preg_match($pattern, $value)) {
                        $errors[$field][] = "El formato del campo " . $displayName . " es inválido.";
                    }
                } elseif ($part === 've_ci') {
                    if ($value !== null && $value !== '' && !preg_match('/^([VE]-[0-9]{5,9}|[0-9]{7,9})$/i', $value)) {
                        $errors[$field][] = "El campo " . $displayName . " debe ser una cédula venezolana válida (Ej: V-12345678).";
                    }
                } elseif ($part === 've_phone') {
                    if ($value !== null && $value !== '' && !preg_match('/^(\+58\d{10}|0\d{10})$/', $value)) {
                        $errors[$field][] = "El campo " . $displayName . " debe ser un teléfono venezolano válido (Ej: +584121234567).";
                    }
                } elseif ($part === 'tech_brand') {
                    $techBrands = ['dell', 'hp', 'lenovo', 'cisco', 'epson', 'logitech', 'sony', 'acer', 'asus', 'apple', 'tp-link', 'd-link', 'viewsonic', 'benq', 'samsung', 'lg', 'toshiba', 'microsoft'];
                    $found = false;
                    foreach ($techBrands as $brand) {
                        if (stripos((string)$value, $brand) !== false) {
                            $found = true;
                            break;
                        }
                    }
                    if ($value !== null && $value !== '' && !$found) {
                        $errors[$field][] = "El campo " . $displayName . " debe mencionar una marca tecnológica reconocida.";
                    }
                } elseif ($part === 'future') {
                    if ($value !== null && $value !== '') {
                        if (strtotime((string)$value) < strtotime(date('Y-m-d H:i'))) {
                            $errors[$field][] = "El campo " . $displayName . " no puede ser una fecha pasada.";
                        }
                    }
                } elseif (strpos($part, 'after_field:') === 0) {
                    $otherField = substr($part, 12);
                    $otherLabel = $finalLabels[$otherField] ?? str_replace('_', ' ', $otherField);
                    $otherValue = $data[$otherField] ?? null;
                    if ($value && $otherValue) {
                        if (strtotime((string)$value) <= strtotime((string)$otherValue)) {
                            $errors[$field][] = "El campo " . $displayName . " debe ser posterior a " . $otherLabel . ".";
                        }
                    }
                } elseif (strpos($part, 'business_hours:') === 0) {
                    $range = substr($part, 15);
                    [$hStart, $hEnd] = explode(':', $range);
                    if ($value !== null && $value !== '') {
                        $hour = (int)date('H', strtotime((string)$value));
                        if ($hour < (int)$hStart || $hour >= (int)$hEnd) {
                            $errors[$field][] = "El campo " . $displayName . " debe estar dentro del horario de operación ($hStart:00 a $hEnd:00).";
                        }
                    }
                } elseif ($part === 'proper_brand_format') {
                    $techBrands = ['Dell', 'HP', 'Lenovo', 'Cisco', 'Epson', 'Logitech', 'Sony', 'Acer', 'Asus', 'Apple', 'TP-Link', 'D-Link', 'ViewSonic', 'BenQ', 'Samsung', 'LG', 'Toshiba', 'Microsoft', 'Western Digital', 'Seagate', 'Kingston'];
                    $regex = '/^(' . implode('|', array_map('preg_quote', $techBrands)) . ')\s+[a-zA-Z0-9\-\.\s\/]+$/';
                    if ($value !== null && $value !== '' && !preg_match($regex, $value)) {
                         $errors[$field][] = "El campo " . $displayName . " debe seguir el formato 'Marca Modelo' con capitalización oficial (Ej: 'Dell Latitude'). Marcas soportadas: " . implode(', ', array_slice($techBrands, 0, 10)) . "...";
                    }
                } elseif (strpos($part, 'in_list:') === 0) {
                    $listStr = substr($part, 8);
                    $allowed = array_map('trim', explode(',', $listStr));
                    if ($value !== null && $value !== '' && !in_array($value, $allowed)) {
                        $errors[$field][] = "El valor seleccionado para " . $displayName . " no es válido.";
                    }
                } elseif (strpos($part, 'unique:') === 0) {
                    $u = substr($part, 7);
                    $uparts = explode(',', $u);
                    $table = trim($uparts[0] ?? '');
                    $col = trim($uparts[1] ?? '');
                    $idCol = trim($uparts[2] ?? '');
                    $idVal = trim($uparts[3] ?? '');

                    if ($table && $col && $value !== null && $value !== '') {
                        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$col} = :v";
                        $params = ['v' => $value];
                        
                        if ($idCol && $idVal) {
                            $sql .= " AND {$idCol} != :idv";
                            $params['idv'] = $idVal;
                        }

                        $stmt = $db->prepare($sql);
                        $stmt->execute($params);
                        $row = $stmt->fetch();
                        if ($row && $row['cnt'] > 0) {
                            $errors[$field][] = "Ese " . $displayName . " ya está registrado.";
                        }
                    }
                } elseif (strpos($part, 'exists:') === 0) {
                    $e = substr($part, 7);
                    $eparts = explode(',', $e);
                    $table = trim($eparts[0] ?? '');
                    $col = trim($eparts[1] ?? '');

                    if ($table && $col && $value !== null && $value !== '') {
                        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$col} = :v";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['v' => $value]);
                        $row = $stmt->fetch();
                        if (!$row || $row['cnt'] == 0) {
                            $errors[$field][] = "El " . $displayName . " seleccionado no es válido o no existe.";
                        }
                    }
                }
            }
        }

        return $errors;
    }
}
