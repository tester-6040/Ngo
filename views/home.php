<?php $title = 'Home'; require __DIR__ . '/layouts/header.php'; ?>
<section class="grid lg:grid-cols-2 gap-8 items-center">
    <div>
        <p class="inline-flex px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold mb-4">Trusted NGO Donation Network</p>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">Donate used dresses with dignity, traceability, and impact.</h1>
        <p class="mt-5 text-lg text-slate-600">A professional platform connecting donors, admins, and orphanages with transparent status tracking and secure role-based workflows.</p>
        <div class="mt-7 flex gap-3 flex-wrap">
            <a href="<?= rtrim($config['base_url'], '/') ?>/register" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-3 rounded-xl font-medium">Start Donating</a>
            <a href="<?= rtrim($config['base_url'], '/') ?>/login" class="bg-white border border-slate-300 hover:border-slate-400 px-5 py-3 rounded-xl font-medium">Portal Login</a>
        </div>
        <div class="mt-7 grid sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-soft"><p class="text-xs text-slate-500">Role Accounts</p><p class="text-xl font-bold">3</p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-soft"><p class="text-xs text-slate-500">Admin Alerts</p><p class="text-xl font-bold">Email</p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-soft"><p class="text-xs text-slate-500">Process</p><p class="text-xl font-bold">Trackable</p></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-soft p-7">
        <h2 class="text-xl font-semibold mb-5">Operational lifecycle</h2>
        <ol class="space-y-3 text-slate-600">
            <li><span class="font-semibold text-indigo-600">1.</span> Donor submits dress details, quantity, and pickup address.</li>
            <li><span class="font-semibold text-violet-600">2.</span> Admin reviews and assigns donation to orphanage.</li>
            <li><span class="font-semibold text-emerald-600">3.</span> Orphanage accepts/rejects and manages received donations.</li>
            <li><span class="font-semibold text-amber-600">4.</span> Teams monitor status through role dashboards.</li>
        </ol>
    </div>
</section>
<?php require __DIR__ . '/layouts/footer.php'; ?>
