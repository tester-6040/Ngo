<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_login();

$role = current_user()['role'];

if ($role === 'admin') {
    header('Location: admin.php');
    exit;
}

if ($role === 'orphanage') {
    header('Location: orphanage.php');
    exit;
}

header('Location: donate.php');
exit;
