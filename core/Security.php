<?php
require_once __DIR__ . '/../config/security.php';

class Security
{

    public static function encrypt($plaintext)
    {
        if ($plaintext === null) return null;
        $key = hash('sha256', APP_ENCRYPTION_KEY, true);
        $iv = openssl_random_pseudo_bytes(16);
        $cipher = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $cipher);
    }

    public static function decrypt($payload)
    {
        if ($payload === null) return null;
        $raw = base64_decode($payload);
        if ($raw === false || strlen($raw) < 17) return null;
        $iv = substr($raw, 0, 16);
        $cipher = substr($raw, 16);
        $key = hash('sha256', APP_ENCRYPTION_KEY, true);
        return openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }


    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
