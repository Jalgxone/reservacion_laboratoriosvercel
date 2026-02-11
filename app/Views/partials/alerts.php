<?php


if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}


if (!function_exists('showFieldError')) {
    function showFieldError($field, $errors) {
        if (isset($errors[$field])) {
            $msg = is_array($errors[$field]) ? $errors[$field][0] : $errors[$field];
            echo '<div class="invalid-feedback">' . htmlspecialchars($msg) . '</div>';
        }
    }
}


if (!function_exists('getSystemMessages')) {
    function getSystemMessages() {
        $msgs = [];
        if (!empty($_SESSION['flash'])) {
            $msg = $_SESSION['flash'];
            $type = 'success';
            if (stripos($msg, 'error') !== false || stripos($msg, 'denegado') !== false || stripos($msg, 'no tiene') !== false) {
                $type = 'error';
            } else if (stripos($msg, 'simulación') !== false || stripos($msg, 'atención') !== false || stripos($msg, 'aviso') !== false) {
                $type = 'warning';
            }
            $msgs[] = ['type' => $type, 'content' => $msg];
            unset($_SESSION['flash']);
        }
        if (!empty($_SESSION['error'])) {
            $msgs[] = ['type' => 'error', 'content' => $_SESSION['error']];
            unset($_SESSION['error']);
        }
        return $msgs;
    }
}


if (!isset($hideGlobalAlerts) || !$hideGlobalAlerts):
    $msgsToDisplay = getSystemMessages();
    $grouped = [];
    foreach ($msgsToDisplay as $m) {
        $grouped[$m['type']][] = $m['content'];
    }

    foreach ($grouped as $type => $contents):
        $title = "Aviso";
        if ($type === 'error') $title = "Atención";
        if ($type === 'success') $title = "Éxito";
        if ($type === 'warning') $title = "Importante";
    ?>
    <div class="alert-static-block alert-static-block-<?= $type ?>">
        <div class="alert-static-block-title">
            <?= $title ?>
        </div>
        <ul class="alert-static-block-list">
            <?php foreach (array_unique($contents) as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
