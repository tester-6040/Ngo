<?php $title = 'Register'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-8">
    <h1 class="text-2xl font-bold mb-4">Create Account</h1>
    <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/register" class="grid md:grid-cols-2 gap-4" id="registerForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input name="name" placeholder="Full name" class="md:col-span-2 border rounded-lg px-3 py-2" required>
        <input name="email" type="email" placeholder="Email" class="border rounded-lg px-3 py-2" required>
        <select name="role" class="border rounded-lg px-3 py-2" required>
            <option value="user">User (Donor)</option>
            <option value="orphanage">Orphanage</option>
        </select>
        <input name="password" type="password" minlength="8" placeholder="Password (min 8 chars)" class="md:col-span-2 border rounded-lg px-3 py-2" required>
        <button class="md:col-span-2 bg-indigo-600 text-white rounded-lg py-2">Register</button>
    </form>
</div>
<script src="<?= rtrim($config['base_url'], '/') ?>/assets/js/validation.js"></script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
