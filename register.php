<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!in_array($role, ['user', 'orphanage'], true)) {
        flash('error', 'Invalid role selected.');
        redirect('register.php');
    }

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        flash('error', 'Provide valid name, email, and password (min 8 characters).');
        redirect('register.php');
    }

    $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt->bind_param('ssss', $name, $email, $hash, $role);
        $stmt->execute();
        flash('success', 'Registration successful. Please login.');
        redirect('login.php');
    } catch (mysqli_sql_exception $e) {
        flash('error', 'Email is already in use.');
        redirect('register.php');
    }
}

require_once __DIR__ . '/layout.php';
render_header('Register');
?>
<div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-soft p-8 md:p-10">
    <h1 class="text-3xl font-semibold mb-2">Create your portal account</h1>
    <p class="text-slate-500 mb-8">Register as a donor or orphanage partner to join the NGO donation network.</p>
    <form method="post" class="grid md:grid-cols-2 gap-4" id="registerForm">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="name">Full name</label>
            <input id="name" class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="name" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="email" type="email" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="role">Account type</label>
            <select id="role" class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="role" required>
                <option value="user">User (Donor)</option>
                <option value="orphanage">Orphanage</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="password">Password</label>
            <input id="password" class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="password" type="password" minlength="8" required>
            <p class="text-xs text-slate-500 mt-1">Use at least 8 characters.</p>
        </div>

        <div class="md:col-span-2 pt-2">
            <button class="w-full bg-brand-700 hover:bg-brand-600 text-white py-2.5 rounded-xl font-medium">Create account</button>
        </div>
    </form>
</div>
<script>
  document.getElementById('registerForm').addEventListener('submit', function (event) {
    const password = this.password.value;
    if (password.length < 8) {
      event.preventDefault();
      alert('Password must be at least 8 characters.');
    }
  });
</script>
<?php render_footer(); ?>
