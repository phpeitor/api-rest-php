<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$projectRoot = dirname(__DIR__);
Dotenv\Dotenv::createImmutable($projectRoot)->safeLoad();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$appName = $_ENV['APP_NAME'] ?? 'API REST PHP';
$appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
$apiBaseUrl = rtrim($_ENV['API_BASE_URL'] ?? ($appUrl . '/api'), '/');

echo json_encode([
    'appName' => $appName,
    'appUrl' => $appUrl,
    'apiBaseUrl' => $apiBaseUrl,
]);
