<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$projectRoot = dirname(__DIR__, 2);
Dotenv\Dotenv::createImmutable($projectRoot)->safeLoad();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (($_ENV['APP_ENV'] ?? 'production') !== 'local') {
    http_response_code(404);
    echo json_encode(['message' => 'No encontrado']);
    exit;
}

echo json_encode(['token' => $_ENV['API_TOKEN'] ?? '']);
