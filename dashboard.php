<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_login();

$role = current_user()['role'];

if ($role === 'admin') {
    redirect('admin');
}

if ($role === 'orphanage') {
    redirect('orphanage');
}

redirect('donate');
