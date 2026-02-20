<?php $title = 'Home'; require __DIR__ . '/layouts/header.php'; ?>
<div class="grid lg:grid-cols-2 gap-8 items-center">
    <div>
        <h1 class="text-5xl font-extrabold leading-tight">Donate Used Dresses to Support Orphanages</h1>
        <p class="mt-4 text-slate-600">A secure and structured NGO platform with role-based dashboards for donors, orphanages, and admin operations.</p>
        <div class="mt-6 flex gap-3">
            <a href="<?= rtrim($config['base_url'], '/') ?>/register" class="bg-indigo-600 text-white px-5 py-3 rounded-xl">Get Started</a>
            <a href="<?= rtrim($config['base_url'], '/') ?>/login" class="border border-slate-300 px-5 py-3 rounded-xl">Login</a>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold">How It Works</h2>
        <ol class="mt-3 space-y-2 text-slate-600 list-decimal list-inside">
            <li>Donor submits dress details and pickup address.</li>
            <li>Admin receives mail, reviews and approves.</li>
            <li>Orphanage accepts or rejects incoming donations.</li>
            <li>All roles track progress in dashboards.</li>
        </ol>
    </div>
</div>
<?php require __DIR__ . '/layouts/footer.php'; ?>
