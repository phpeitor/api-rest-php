<?php

declare(strict_types=1);

/**
 * Generador de tokens JWT para desarrollo y testing.
 *
 * Uso:
 * php bin/generate-token.php
 * php bin/generate-token.php --name="John Doe" --company="My Company" --exp=3600
 *
 * Ejemplo:
 * php bin/generate-token.php --name="Test User" --company="Test Corp"
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Cargar .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

function getArg(string $name, string $default = ''): string
{
    global $argv;
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--' . $name . '=')) {
            return substr($arg, strlen('--' . $name . '='));
        }
    }
    return $default;
}

// Configuración del token
$secretKey = bin2hex(random_bytes(32));
$issuedAt = time();
$expiresIn = (int) getArg('exp', '86400'); // 24 horas por defecto
$expire = $issuedAt + $expiresIn;

$payload = [
    'iat' => $issuedAt,
    'exp' => $expire,
    'unique_name' => getArg('name', 'DEV.USER'),
    'company' => getArg('company', 'DEV_COMPANY'),
];

// Generar el token usando la misma clave (en producción, usar una clave segura)
$token = JWT::encode($payload, $secretKey, 'HS256');

echo "Token generado correctamente:\n";
echo "================================\n";
echo "Token: $token\n";
echo "Válido por: $expiresIn segundos\n";
echo "Expira en: " . date('Y-m-d H:i:s', $expire) . "\n";
echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
echo "\n";
echo "IMPORTANTE: Para usar este token:\n";
echo "1. Copia el token generado arriba.\n";
echo "2. Añade este valor a tu archivo .env como: API_TOKEN=$token\n";
echo "3. Reinicia tu servidor web.\n";
echo "4. Usa el token en el header: Authorization: Bearer $token\n";
