<?php $title = 'Login'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="grid lg:grid-cols-2 bg-white rounded-2xl overflow-hidden shadow">
    <div class="bg-slate-900 text-white p-8">
        <h1 class="text-3xl font-bold">Secure Login</h1>
        <p class="mt-3 text-slate-300">Login as Admin, User, or Orphanage.</p>
    </div>
    <div class="p-8">
        <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/login" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input name="email" type="email" placeholder="Email" class="w-full border rounded-lg px-3 py-2" required>
            <input name="password" type="password" placeholder="Password" class="w-full border rounded-lg px-3 py-2" required>
            <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">Login as...</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="orphanage">Orphanage</option>
            </select>
            <button class="w-full bg-indigo-600 text-white rounded-lg py-2">Login</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
