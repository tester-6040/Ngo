<?php

declare(strict_types=1);

require_once __DIR__ . '/layout.php';

render_header('Home');
?>
<section class="grid lg:grid-cols-2 gap-8 items-center">
    <div>
        <p class="inline-flex items-center px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-semibold mb-4">Trusted NGO Donation Network</p>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-tight">Professional dress donation workflow for social impact.</h1>
        <p class="mt-5 text-lg text-slate-600 max-w-xl">Connect donors, administrators, and orphanage teams in one reliable system with status visibility, assignment control, and timely notifications.</p>
        <div class="mt-7 flex flex-wrap gap-3">
            <a href="<?= h(route_url('register')) ?>" class="bg-brand-700 hover:bg-brand-600 text-white px-5 py-3 rounded-xl font-medium">Start Donating</a>
            <a href="<?= h(route_url('login')) ?>" class="bg-white border border-slate-300 hover:border-slate-400 px-5 py-3 rounded-xl font-medium">Portal Login</a>
        </div>
        <div class="mt-8 grid sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-xs text-slate-500">Role Accounts</p><p class="text-xl font-semibold">3</p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-xs text-slate-500">Email Alerts</p><p class="text-xl font-semibold">Instant</p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-xs text-slate-500">Workflow</p><p class="text-xl font-semibold">Trackable</p></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-slate-200 p-7">
        <h2 class="text-xl font-semibold mb-5">Operational lifecycle</h2>
        <div class="space-y-4 text-slate-600">
            <div class="flex gap-4"><span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold grid place-content-center">1</span><p>Donor submits dress details and quantity.</p></div>
            <div class="flex gap-4"><span class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 font-semibold grid place-content-center">2</span><p>Admin receives an alert and verifies donation readiness.</p></div>
            <div class="flex gap-4"><span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-semibold grid place-content-center">3</span><p>Donation is assigned to an orphanage for pickup/distribution.</p></div>
            <div class="flex gap-4"><span class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 font-semibold grid place-content-center">4</span><p>Orphanage marks delivery complete and progress remains auditable.</p></div>
        </div>
    </div>
</section>
<?php render_footer(); ?>
