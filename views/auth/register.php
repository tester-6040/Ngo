<?php $title = 'Register'; require __DIR__ . '/../layouts/header.php'; ?>
<section class="max-w-2xl mx-auto bg-white rounded-2xl shadow-soft border border-slate-200 p-8">
    <h1 class="text-2xl font-semibold">Create account</h1>
    <p class="text-slate-500 mt-1 mb-5">Register as a donor or orphanage partner.</p>
    <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/register" class="grid md:grid-cols-2 gap-4" id="registerForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="md:col-span-2">
            <label class="text-sm font-medium">Full name</label>
            <input name="name" placeholder="Full name" class="mt-1 w-full border rounded-xl px-3 py-2.5" required>
        </div>
        <div>
            <label class="text-sm font-medium">Email</label>
            <input name="email" type="email" placeholder="name@example.com" class="mt-1 w-full border rounded-xl px-3 py-2.5" required>
        </div>
        <div>
            <label class="text-sm font-medium">Role</label>
            <select name="role" class="mt-1 w-full border rounded-xl px-3 py-2.5" required>
                <option value="user">User (Donor)</option>
                <option value="orphanage">Orphanage</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium">Password</label>
            <input name="password" type="password" minlength="8" placeholder="Minimum 8 characters" class="mt-1 w-full border rounded-xl px-3 py-2.5" required>
        </div>
        <button class="md:col-span-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl py-2.5 font-medium">Register</button>
    </form>
</section>
<script src="<?= rtrim($config['base_url'], '/') ?>/assets/js/validation.js"></script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
