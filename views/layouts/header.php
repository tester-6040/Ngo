<?php
use Core\Session;

$user = Session::user();
$base = rtrim($config['base_url'], '/');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dress Donation Platform') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 flex flex-col">
<nav class="bg-white border-b border-slate-200 sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a class="font-semibold text-lg" href="<?= $base ?>/">Dress Donation Platform</a>
        <div class="flex items-center gap-3 text-sm">
            <?php if ($user): ?>
                <a href="<?= $base ?>/dashboard" class="text-slate-600 hover:text-slate-900">Dashboard</a>
                <span class="px-2 py-1 rounded-full bg-indigo-100 text-indigo-700"><?= htmlspecialchars($user['role']) ?></span>
                <span class="text-slate-500 hidden md:block"><?= htmlspecialchars($user['name']) ?></span>
                <form method="post" action="<?= $base ?>/logout">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf ?? '') ?>">
                    <button class="bg-slate-900 text-white px-3 py-1.5 rounded-lg">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= $base ?>/login">Login</a>
                <a href="<?= $base ?>/register" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="max-w-7xl w-full mx-auto px-6 py-8 flex-1">
<?php if ($flashSuccess = Session::get('flash_success')): Session::remove('flash_success'); ?>
    <div class="mb-4 p-3 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-lg"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<?php if ($flashError = Session::get('flash_error')): Session::remove('flash_error'); ?>
    <div class="mb-4 p-3 border border-rose-200 bg-rose-50 text-rose-700 rounded-lg"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>
