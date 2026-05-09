<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Ejecuta la migracion SQL de forma automatica.
 *
 * Uso CLI:
 * php db/migrate.php
 * php db/migrate.php --host=localhost --user=root --password=
 *
 * Uso web:
 * http://localhost/api-rest-php/db/migrate.php
 * http://localhost/api-rest-php/db/migrate.php?host=localhost&user=root&password=
 */

function isCli(): bool
{
    return PHP_SAPI === 'cli';
}

function outputLine(string $message): void
{
    if (isCli()) {
        echo $message . PHP_EOL;
        return;
    }

    echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '<br>';
}

function getOption(string $name, string $default = ''): string
{
    if (isCli()) {
        $options = getopt('', [$name . '::']);
        if (isset($options[$name]) && $options[$name] !== false) {
            return (string) $options[$name];
        }
        return $default;
    }

    if (isset($_GET[$name])) {
        return (string) $_GET[$name];
    }

    return $default;
}

function envValue(string $key, string $default = ''): string
{
    if (isset($_ENV[$key])) {
        return (string) $_ENV[$key];
    }

    if (isset($_SERVER[$key])) {
        return (string) $_SERVER[$key];
    }

    $value = getenv($key);
    if ($value !== false) {
        return (string) $value;
    }

    return $default;
}

function splitSqlStatements(string $sql): array
{
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*![\s\S]*?\*\//', '', $sql);

    $parts = preg_split('/;\s*(\r\n|\r|\n)/', (string) $sql);
    if ($parts === false) {
        return [];
    }

    $statements = [];
    foreach ($parts as $part) {
        $statement = trim($part);
        if ($statement !== '') {
            $statements[] = $statement;
        }
    }

    return $statements;
}

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$host = getOption('host', envValue('DB_HOST', 'localhost'));
$user = getOption('user', envValue('DB_USER', 'root'));
$password = getOption('password', envValue('DB_PASSWORD', ''));

$sqlFile = __DIR__ . '/test.sql';

if (!is_file($sqlFile)) {
    http_response_code(500);
    outputLine('ERROR: No se encontro el archivo db/test.sql');
    exit(1);
}

$sqlContent = file_get_contents($sqlFile);
if ($sqlContent === false) {
    http_response_code(500);
    outputLine('ERROR: No se pudo leer db/test.sql');
    exit(1);
}

$statements = splitSqlStatements($sqlContent);
if (count($statements) === 0) {
    http_response_code(500);
    outputLine('ERROR: No hay sentencias SQL para ejecutar en db/test.sql');
    exit(1);
}

try {
    $dsn = 'mysql:host=' . $host . ';charset=utf8mb4';
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    foreach ($statements as $statement) {
        $pdo->exec($statement);
    }

    outputLine('Migracion completada correctamente.');
    outputLine('Base de datos: bd_test');
    outputLine('Tabla: usuario');
    outputLine('Datos de prueba: cargados');
} catch (Throwable $e) {
    http_response_code(500);
    outputLine('ERROR en migracion: ' . $e->getMessage());
    exit(1);
}
