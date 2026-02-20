<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

render_header('Donate Used Dresses to Orphanages');
?>
<section class="grid md:grid-cols-2 gap-8 items-center">
    <div>
        <h1 class="text-4xl font-extrabold mb-4">Share Your Used Dresses, Spread Warmth</h1>
        <p class="text-slate-600 mb-6">This NGO platform connects donors with orphanages. Users can donate clothes, orphanages can track incoming donations, and admins can coordinate everything transparently.</p>
        <div class="flex gap-3">
            <a href="register.php" class="bg-blue-700 text-white px-4 py-2 rounded-md">Get Started</a>
            <a href="login.php" class="bg-white border border-slate-300 px-4 py-2 rounded-md">Login</a>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4">How it works</h2>
        <ol class="space-y-3 text-slate-700 list-decimal list-inside">
            <li>User registers and submits dress donation details.</li>
            <li>System automatically alerts admin via email.</li>
            <li>Admin reviews and assigns to an orphanage.</li>
            <li>Orphanage logs in and tracks assigned donations.</li>
        </ol>
    </div>
</section>
<?php render_footer(); ?>
