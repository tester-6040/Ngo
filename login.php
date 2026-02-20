<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user['password_hash']) || $user['role'] !== $role) {
        flash('error', 'Invalid credentials or wrong role selected.');
        redirect('login.php');
    }

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    flash('success', 'Welcome back, ' . $user['name'] . '.');
    redirect('dashboard.php');
}

require_once __DIR__ . '/layout.php';
render_header('Login');
?>
<section class="min-h-[72vh] grid lg:grid-cols-2 rounded-3xl overflow-hidden shadow-soft border border-slate-200">
    <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-brand-800 text-white p-10 flex flex-col justify-between">
        <div>
            <p class="text-brand-100 text-sm font-semibold tracking-wider uppercase">Secure Portal Access</p>
            <h1 class="mt-4 text-3xl font-semibold leading-tight">A professional operations portal for NGO teams.</h1>
            <p class="mt-4 text-slate-300">Designed for role-specific access and clear operational control across donations and assignments.</p>
        </div>
        <div class="grid gap-2 text-sm text-slate-200">
            <p>✓ Admin: verify and route donations</p>
            <p>✓ User: submit and monitor donation status</p>
            <p>✓ Orphanage: receive and complete assignments</p>
        </div>
    </div>

    <div class="bg-white p-10">
        <div class="max-w-md">
            <h2 class="text-2xl font-semibold mb-1">Sign in</h2>
            <p class="text-slate-500 mb-6">Enter your credentials to access your dashboard.</p>
            <form method="post" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <div>
                    <label class="block text-sm font-medium mb-1" for="email">Email address</label>
                    <input id="email" class="w-full border border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 rounded-xl px-3 py-2.5" name="email" type="email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="password">Password</label>
                    <input id="password" class="w-full border border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 rounded-xl px-3 py-2.5" name="password" type="password" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="role">Role</label>
                    <select id="role" class="w-full border border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 rounded-xl px-3 py-2.5" name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User (Donor)</option>
                        <option value="orphanage">Orphanage</option>
                    </select>
                </div>
                <button class="w-full bg-brand-700 hover:bg-brand-600 text-white py-2.5 rounded-xl font-medium">Login securely</button>
            </form>
        </div>
    </div>
</section>
<?php render_footer(); ?>
