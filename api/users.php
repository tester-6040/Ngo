<?php

declare(strict_types=1);

$controller = require __DIR__ . '/bootstrap.php';
$controller->users($_SERVER['REQUEST_METHOD'] ?? 'GET');
