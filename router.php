<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = APP_BASE_PATH === '' ? '' : '/' . trim(APP_BASE_PATH, '/');

if ($basePath !== '' && str_starts_with($uriPath, $basePath)) {
    $uriPath = substr($uriPath, strlen($basePath));
    $uriPath = $uriPath === '' ? '/' : $uriPath;
}

if (preg_match('#^/([a-z0-9_-]+)\.php$#i', $uriPath, $matches)) {
    $target = $matches[1] === 'index' ? '/' : '/' . $matches[1];
    header('Location: ' . $basePath . $target, true, 301);
    exit;
}

$file = __DIR__ . $uriPath;
if ($uriPath !== '/' && is_file($file)) {
    return false;
}

$routes = [
    '/' => __DIR__ . '/index.php',
    '/login' => __DIR__ . '/login.php',
    '/register' => __DIR__ . '/register.php',
    '/dashboard' => __DIR__ . '/dashboard.php',
    '/donate' => __DIR__ . '/donate.php',
    '/admin' => __DIR__ . '/admin.php',
    '/orphanage' => __DIR__ . '/orphanage.php',
    '/logout' => __DIR__ . '/logout.php',
];

if (isset($routes[$uriPath])) {
    require $routes[$uriPath];
    exit;
}

http_response_code(404);
require __DIR__ . '/index.php';
