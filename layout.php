<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function render_header(string $title): void
{
    $user = current_user();
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title><?= h($title) ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-50 min-h-screen text-slate-900">
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a class="font-bold text-xl text-blue-700" href="index.php">NGO Dress Donation</a>
            <div class="flex items-center gap-4 text-sm">
                <?php if ($user): ?>
                    <a class="hover:text-blue-600" href="dashboard.php">Dashboard</a>
                    <span class="text-slate-500"><?= h($user['name']) ?> (<?= h($user['role']) ?>)</span>
                    <a class="bg-slate-900 text-white px-3 py-1 rounded-md" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="hover:text-blue-600" href="login.php">Login</a>
                    <a class="bg-blue-700 text-white px-3 py-1 rounded-md" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-6">
    <?php

    foreach (consume_flash() as $item) {
        $styles = $item['type'] === 'success'
            ? 'bg-green-100 border-green-300 text-green-800'
            : 'bg-red-100 border-red-300 text-red-800';

        echo '<div class="mb-4 border rounded px-3 py-2 ' . $styles . '">' . h($item['message']) . '</div>';
    }
}

function render_footer(): void
{
    ?>
    </main>
    </body>
    </html>
    <?php
}
