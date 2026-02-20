<?php
use Core\Session;

$user = Session::user();
$base = rtrim($config['base_url'], '/');
$roleColors = [
    'admin' => 'bg-violet-100 text-violet-700',
    'user' => 'bg-blue-100 text-blue-700',
    'orphanage' => 'bg-emerald-100 text-emerald-700',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dress Donation Platform') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'] },
            boxShadow: {
              soft: '0 12px 30px rgba(15,23,42,0.08)'
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 flex flex-col font-sans antialiased">
<div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_42%),radial-gradient(circle_at_bottom_left,rgba(16,185,129,0.10),transparent_40%)]"></div>
<nav class="bg-white/90 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a class="font-semibold text-lg tracking-tight" href="<?= $base ?>/">Dress Donation Platform</a>
        <div class="flex items-center gap-3 text-sm">
            <?php if ($user): ?>
                <a href="<?= $base ?>/dashboard" class="text-slate-600 hover:text-slate-900">Dashboard</a>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $roleColors[$user['role']] ?? 'bg-slate-100 text-slate-700' ?>">
                    <?= htmlspecialchars(ucfirst($user['role'])) ?>
                </span>
                <span class="text-slate-500 hidden md:block"><?= htmlspecialchars($user['name']) ?></span>
                <form method="post" action="<?= $base ?>/logout">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf ?? '') ?>">
                    <button class="bg-slate-900 text-white px-3.5 py-1.5 rounded-lg hover:bg-slate-800">Logout</button>
                </form>
            <?php else: ?>
                <a class="text-slate-600 hover:text-slate-900" href="<?= $base ?>/login">Login</a>
                <a href="<?= $base ?>/register" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3.5 py-1.5 rounded-lg">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="max-w-7xl w-full mx-auto px-6 py-8 flex-1">
<?php if ($flashSuccess = Session::get('flash_success')): Session::remove('flash_success'); ?>
    <div class="mb-4 p-3 border border-emerald-200 bg-emerald-50 text-emerald-700 rounded-lg shadow-sm"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<?php if ($flashError = Session::get('flash_error')): Session::remove('flash_error'); ?>
    <div class="mb-4 p-3 border border-rose-200 bg-rose-50 text-rose-700 rounded-lg shadow-sm"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>
