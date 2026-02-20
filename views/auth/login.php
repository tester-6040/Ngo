<?php $title = 'Login'; require __DIR__ . '/../layouts/header.php'; ?>
<section class="grid lg:grid-cols-2 bg-white rounded-3xl overflow-hidden shadow-soft border border-slate-200">
    <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-800 text-white p-10">
        <h1 class="text-3xl font-bold leading-tight">Secure role-based portal login</h1>
        <p class="mt-3 text-slate-300">Access dedicated dashboards for Admin, User, and Orphanage operations.</p>
        <ul class="mt-6 space-y-2 text-sm text-slate-200">
            <li>✓ Admin: approve & assign donations</li>
            <li>✓ User: submit and track donation history</li>
            <li>✓ Orphanage: accept/reject incoming requests</li>
        </ul>
    </div>
    <div class="p-10">
        <h2 class="text-2xl font-semibold mb-5">Sign in</h2>
        <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/login" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div>
                <label class="text-sm font-medium">Email</label>
                <input name="email" type="email" placeholder="name@example.com" class="mt-1 w-full border border-slate-300 rounded-xl px-3 py-2.5" required>
            </div>
            <div>
                <label class="text-sm font-medium">Password</label>
                <input name="password" type="password" placeholder="••••••••" class="mt-1 w-full border border-slate-300 rounded-xl px-3 py-2.5" required>
            </div>
            <div>
                <label class="text-sm font-medium">Role</label>
                <select name="role" class="mt-1 w-full border border-slate-300 rounded-xl px-3 py-2.5" required>
                    <option value="">Login as...</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="orphanage">Orphanage</option>
                </select>
            </div>
            <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl py-2.5 font-medium">Login Securely</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
