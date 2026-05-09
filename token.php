<?php
// Dev-only: devuelve el token estático definido en includes/Client.class.php
header('Content-Type: application/json; charset=utf-8');

@include_once __DIR__ . '/includes/Client.class.php';

$t = '';
if (defined('token')) {
    $t = token;
}

echo json_encode(['token' => $t]);
