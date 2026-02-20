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
    <div class="bg-slate-900 text-white p-10 flex flex-col justify-between">
        <div>
            <p class="text-blue-300 text-sm font-semibold tracking-wider uppercase">Secure Access</p>
            <h1 class="mt-4 text-3xl font-semibold leading-tight">Welcome to the NGO operations portal.</h1>
            <p class="mt-4 text-slate-300">Login with your role-specific account to manage donations smoothly and professionally.</p>
        </div>
        <ul class="space-y-3 text-slate-300 text-sm">
            <li>• Admin: review and assign donations</li>
            <li>• User: submit and track dress donations</li>
            <li>• Orphanage: view assigned contributions</li>
        </ul>
    </div>
    <div class="bg-white p-10">
        <h2 class="text-2xl font-semibold mb-6">Sign in</h2>
        <form method="post" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input class="w-full border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl px-3 py-2.5" name="email" type="email" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input class="w-full border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl px-3 py-2.5" name="password" type="password" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select class="w-full border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl px-3 py-2.5" name="role" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User (Donor)</option>
                    <option value="orphanage">Orphanage</option>
                </select>
            </div>
            <button class="w-full bg-brand-700 hover:bg-brand-600 text-white py-2.5 rounded-xl font-medium">Login securely</button>
        </form>
    </div>
</section>
<?php render_footer(); ?>
