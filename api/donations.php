<?php

declare(strict_types=1);

$controller = require __DIR__ . '/bootstrap.php';
$controller->donations($_SERVER['REQUEST_METHOD'] ?? 'GET');
