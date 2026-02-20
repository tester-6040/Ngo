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
        <p class="text-slate-500 mb-5">Provide clean, well-labeled dress details for faster assignment.</p>
        <form method="post" class="space-y-3" id="donationForm">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div>
                <label class="block text-sm font-medium mb-1">Donation title</label>
                <input class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="title" placeholder="e.g. Kids winter dresses" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="description" placeholder="Condition, age group, notes" rows="4" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Quantity</label>
                <input class="w-full border border-slate-300 rounded-xl px-3 py-2.5" name="quantity" type="number" min="1" placeholder="Number of dresses" required>
            </div>
            <button class="w-full bg-brand-700 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl font-medium">Submit Donation</button>
        </form>
    </section>

    <section class="xl:col-span-2 space-y-4">
        <div class="grid sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-slate-500 text-sm">Total</p><p class="text-2xl font-semibold mt-1"><?= h((string) count($donations)) ?></p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-slate-500 text-sm">Pending</p><p class="text-2xl font-semibold mt-1"><?= h((string) $pending) ?></p></div>
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-slate-500 text-sm">Processed</p><p class="text-2xl font-semibold mt-1"><?= h((string) (count($donations) - $pending)) ?></p></div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h2 class="text-xl font-semibold">Donation history</h2>
            </div>
            <?php if (!$donations): ?>
                <div class="p-6 text-slate-500">No donations submitted yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold">Title</th>
                                <th class="text-left px-4 py-3 font-semibold">Quantity</th>
                                <th class="text-left px-4 py-3 font-semibold">Status</th>
                                <th class="text-left px-4 py-3 font-semibold">Assigned to</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td class="px-4 py-4 min-w-[300px]"><p class="font-medium"><?= h($donation['title']) ?></p><p class="text-slate-500 mt-1"><?= h($donation['description']) ?></p></td>
                                <td class="px-4 py-4"><?= h((string) $donation['quantity']) ?></td>
                                <td class="px-4 py-4"><span class="text-xs px-2.5 py-1 rounded-full <?= $donation['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' ?>"><?= h($donation['status']) ?></span></td>
                                <td class="px-4 py-4"><?= h($donation['orphanage_name'] ?? 'Pending assignment') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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
