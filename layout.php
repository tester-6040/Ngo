<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function role_badge(string $role): string
{
    $classes = [
        'admin' => 'bg-violet-100 text-violet-700',
        'user' => 'bg-blue-100 text-blue-700',
        'orphanage' => 'bg-emerald-100 text-emerald-700',
    ];

    return $classes[$role] ?? 'bg-slate-100 text-slate-700';
}

function render_header(string $title): void
{
    $user = current_user();
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title><?= h($title) ?> · NGO Dress Donation</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
              theme: {
                extend: {
                  fontFamily: {
                    sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                  },
                  colors: {
                    brand: {
                      50: '#eef2ff',
                      100: '#e0e7ff',
                      600: '#4f46e5',
                      700: '#4338ca',
                      800: '#3730a3'
                    }
                  },
                  boxShadow: {
                    soft: '0 12px 35px rgba(15, 23, 42, 0.10)',
                    card: '0 6px 18px rgba(15, 23, 42, 0.07)'
                  }
                }
              }
            }
        </script>
    </head>
    <body class="bg-slate-100 text-slate-900 min-h-screen font-sans antialiased">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,_rgba(99,102,241,0.16),_transparent_42%),radial-gradient(circle_at_bottom_left,_rgba(16,185,129,0.13),_transparent_40%)]"></div>
    <nav class="sticky top-0 z-20 backdrop-blur bg-white/90 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="font-semibold text-slate-900 tracking-tight text-lg">NGO Dress Donation</a>
            <div class="flex items-center gap-3 text-sm">
                <?php if ($user): ?>
                    <a class="text-slate-600 hover:text-slate-900" href="dashboard.php">Dashboard</a>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium <?= role_badge($user['role']) ?>">
                        <?= h(ucfirst($user['role'])) ?>
                    </span>
                    <span class="text-slate-500 hidden md:block"><?= h($user['name']) ?></span>
                    <a class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="text-slate-600 hover:text-slate-900" href="login.php">Login</a>
                    <a class="bg-brand-700 hover:bg-brand-600 text-white px-4 py-2 rounded-lg" href="register.php">Create account</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-6 py-10">
    <?php

    foreach (consume_flash() as $item) {
        $styles = $item['type'] === 'success'
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
            : 'bg-rose-50 border-rose-200 text-rose-800';

        echo '<div class="mb-5 border rounded-xl px-4 py-3 shadow-sm ' . $styles . '">' . h($item['message']) . '</div>';
    }
}

function render_footer(): void
{
    ?>
    </main>
    <footer class="border-t border-slate-200/80 bg-white/80">
        <div class="max-w-7xl mx-auto px-6 py-5 text-sm text-slate-500 flex flex-wrap items-center justify-between gap-2">
            <p>© <?= h((string) date('Y')) ?> NGO Dress Donation Platform</p>
            <p>Secure role-based portal for donors, admin, and orphanage teams.</p>
        </div>
    </footer>
    </body>
    </html>
    <?php
}
