<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('user');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $qty = (int) ($_POST['quantity'] ?? 0);

    if ($title === '' || $description === '' || $qty < 1) {
        flash('error', 'All fields are required and quantity must be at least 1.');
        redirect('donate.php');
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
    redirect('donate.php');
}

require_once __DIR__ . '/layout.php';
render_header('Donor Dashboard');

$userId = current_user()['id'];
$stmt = db()->prepare('SELECT d.*, o.name AS orphanage_name FROM donations d LEFT JOIN users o ON o.id = d.orphanage_id WHERE d.user_id = ? ORDER BY d.created_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$donations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$pending = count(array_filter($donations, static fn($d) => $d['status'] === 'pending'));
?>
<div class="grid xl:grid-cols-3 gap-6">
    <section class="xl:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-soft p-6 h-fit">
        <h1 class="text-2xl font-semibold mb-1">Submit donation</h1>
        <p class="text-slate-500 mb-5">Share details of dresses ready for donation.</p>
        <form method="post" class="space-y-3" id="donationForm">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="title" placeholder="Donation title" required>
            <textarea class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="description" placeholder="Condition, age group, notes" rows="4" required></textarea>
            <input class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="quantity" type="number" min="1" placeholder="Number of dresses" required>
            <button class="w-full bg-brand-700 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl font-medium">Submit Donation</button>
        </form>
    </section>

    <section class="xl:col-span-2 space-y-4">
        <div class="grid sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <p class="text-slate-500 text-sm">Total Donations</p>
                <p class="text-2xl font-semibold mt-1"><?= h((string) count($donations)) ?></p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <p class="text-slate-500 text-sm">Pending</p>
                <p class="text-2xl font-semibold mt-1"><?= h((string) $pending) ?></p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <p class="text-slate-500 text-sm">Assigned</p>
                <p class="text-2xl font-semibold mt-1"><?= h((string) (count($donations) - $pending)) ?></p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-soft p-6">
            <h2 class="text-xl font-semibold mb-4">Donation history</h2>
            <?php if (!$donations): ?>
                <p class="text-slate-500">No donations submitted yet.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($donations as $donation): ?>
                        <article class="border border-slate-200 rounded-xl p-4">
                            <div class="flex flex-wrap gap-2 justify-between">
                                <p class="font-medium"><?= h($donation['title']) ?> (x<?= h((string) $donation['quantity']) ?>)</p>
                                <span class="text-xs px-2.5 py-1 rounded-full <?= $donation['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' ?>"><?= h($donation['status']) ?></span>
                            </div>
                            <p class="text-sm text-slate-600 mt-1"><?= h($donation['description']) ?></p>
                            <?php if ($donation['orphanage_name']): ?>
                                <p class="text-xs mt-2 text-slate-500">Assigned to: <?= h($donation['orphanage_name']) ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
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
