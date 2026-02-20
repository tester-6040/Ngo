<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('user');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $qty = (int) ($_POST['quantity'] ?? 0);

    if ($title === '' || $description === '' || $qty < 1) {
        flash('error', 'All fields are required and quantity must be at least 1.');
        header('Location: donate.php');
        exit;
    }

    $user = current_user();
    $status = 'pending';

    $stmt = db()->prepare('INSERT INTO donations (user_id, title, description, quantity, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issis', $user['id'], $title, $description, $qty, $status);
    $stmt->execute();

    $subject = 'New Dress Donation Submitted';
    $body = "A new donation has been submitted by {$user['name']} ({$user['email']}).\n"
        . "Title: {$title}\nQuantity: {$qty}\nDescription: {$description}\n"
        . "Please login as admin to review and assign.";
    send_admin_mail($subject, $body);

    flash('success', 'Donation submitted successfully. Admin has been notified by email.');
    header('Location: donate.php');
    exit;
}

require_once __DIR__ . '/layout.php';
render_header('Donate Dresses');

$userId = current_user()['id'];
$stmt = db()->prepare('SELECT d.*, o.name AS orphanage_name FROM donations d LEFT JOIN users o ON o.id = d.orphanage_id WHERE d.user_id = ? ORDER BY d.created_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$donations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="grid lg:grid-cols-2 gap-6">
    <section class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Donate Used Dresses</h1>
        <form method="post" class="space-y-3" id="donationForm">
            <input class="w-full border rounded px-3 py-2" name="title" placeholder="Donation title (e.g., Kids Winter Dresses)" required>
            <textarea class="w-full border rounded px-3 py-2" name="description" placeholder="Condition, age group, notes" rows="4" required></textarea>
            <input class="w-full border rounded px-3 py-2" name="quantity" type="number" min="1" placeholder="Number of dresses" required>
            <button class="bg-blue-700 text-white px-4 py-2 rounded">Submit Donation</button>
        </form>
    </section>

    <section class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">My Donations</h2>
        <?php if (!$donations): ?>
            <p class="text-slate-500">No donations submitted yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($donations as $donation): ?>
                    <article class="border rounded p-3">
                        <p class="font-semibold"><?= h($donation['title']) ?> (x<?= h((string) $donation['quantity']) ?>)</p>
                        <p class="text-sm text-slate-600"><?= h($donation['description']) ?></p>
                        <p class="text-xs mt-1 text-slate-500">Status: <?= h($donation['status']) ?>
                            <?php if ($donation['orphanage_name']): ?>
                                | Assigned to: <?= h($donation['orphanage_name']) ?>
                            <?php endif; ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
  document.getElementById('donationForm').addEventListener('submit', function(event) {
    const quantity = parseInt(this.quantity.value, 10);
    if (Number.isNaN(quantity) || quantity < 1) {
      event.preventDefault();
      alert('Quantity must be at least 1.');
    }
  });
</script>
<?php render_footer(); ?>
