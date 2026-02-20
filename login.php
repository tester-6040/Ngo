<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user['password_hash']) || $user['role'] !== $role) {
        flash('error', 'Invalid credentials or wrong role selected.');
        header('Location: login.php');
        exit;
    }

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    flash('success', 'Welcome back, ' . $user['name'] . '!');
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/layout.php';
render_header('Login');
?>
<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-4">Login</h1>
    <form method="post" class="space-y-4">
        <input class="w-full border rounded px-3 py-2" name="email" placeholder="Email" type="email" required>
        <input class="w-full border rounded px-3 py-2" name="password" placeholder="Password" type="password" required>
        <select class="w-full border rounded px-3 py-2" name="role" required>
            <option value="">Login as...</option>
            <option value="admin">Admin</option>
            <option value="user">User (Donor)</option>
            <option value="orphanage">Orphanage</option>
        </select>
        <button class="w-full bg-blue-700 text-white py-2 rounded">Login</button>
    </form>
</div>
<?php render_footer(); ?>
