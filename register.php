<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!in_array($role, ['user', 'orphanage'], true)) {
        flash('error', 'Invalid role selected.');
        header('Location: register.php');
        exit;
    }

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        flash('error', 'Provide valid name, email, and password (min 6 characters).');
        header('Location: register.php');
        exit;
    }

    $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt->bind_param('ssss', $name, $email, $hash, $role);
        $stmt->execute();
        flash('success', 'Registration successful. Please login.');
        header('Location: login.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        flash('error', 'Email is already in use.');
        header('Location: register.php');
        exit;
    }
}

require_once __DIR__ . '/layout.php';
render_header('Register');
?>
<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-4">Create Account</h1>
    <form method="post" class="space-y-4" id="registerForm">
        <input class="w-full border rounded px-3 py-2" name="name" placeholder="Full name" required>
        <input class="w-full border rounded px-3 py-2" name="email" placeholder="Email" type="email" required>
        <input class="w-full border rounded px-3 py-2" name="password" placeholder="Password" type="password" minlength="6" required>
        <select class="w-full border rounded px-3 py-2" name="role" required>
            <option value="user">User (Donor)</option>
            <option value="orphanage">Orphanage</option>
        </select>
        <button class="w-full bg-blue-700 text-white py-2 rounded">Register</button>
    </form>
</div>
<script>
  document.getElementById('registerForm').addEventListener('submit', function (event) {
    const password = this.password.value;
    if (password.length < 6) {
      event.preventDefault();
      alert('Password must be at least 6 characters.');
    }
  });
</script>
<?php render_footer(); ?>
