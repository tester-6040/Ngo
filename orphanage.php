<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('orphanage');

$orphanageId = current_user()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $donationId = (int) ($_POST['donation_id'] ?? 0);
    $status = 'completed';

    if ($donationId > 0) {
        $stmt = db()->prepare('UPDATE donations SET status = ? WHERE id = ? AND orphanage_id = ?');
        $stmt->bind_param('sii', $status, $donationId, $orphanageId);
        $stmt->execute();
        flash('success', 'Donation marked as completed.');
    }

    redirect('orphanage.php');
}

require_once __DIR__ . '/layout.php';
render_header('Orphanage Dashboard');

$stmt = db()->prepare('SELECT d.*, u.name AS donor_name FROM donations d JOIN users u ON u.id = d.user_id WHERE d.orphanage_id = ? ORDER BY d.created_at DESC');
$stmt->bind_param('i', $orphanageId);
$stmt->execute();
$donations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$completed = count(array_filter($donations, static fn($d) => $d['status'] === 'completed'));
?>
<div class="space-y-5">
    <div class="grid sm:grid-cols-3 gap-3">
        <div class="bg-white border border-slate-200 rounded-xl p-4"><p class="text-sm text-slate-500">Assigned</p><p class="text-2xl font-semibold"><?= h((string) count($donations)) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4"><p class="text-sm text-slate-500">Completed</p><p class="text-2xl font-semibold"><?= h((string) $completed) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4"><p class="text-sm text-slate-500">In Progress</p><p class="text-2xl font-semibold"><?= h((string) (count($donations) - $completed)) ?></p></div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-soft p-6">
        <h1 class="text-2xl font-semibold mb-4">Assigned donations</h1>
        <?php if (!$donations): ?>
            <p class="text-slate-500">No donations assigned yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($donations as $donation): ?>
                    <article class="border border-slate-200 rounded-xl p-4">
                        <div class="flex justify-between flex-wrap gap-2">
                            <p class="font-medium"><?= h($donation['title']) ?> (x<?= h((string) $donation['quantity']) ?>)</p>
                            <span class="text-xs px-2.5 py-1 rounded-full <?= $donation['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' ?>"><?= h($donation['status']) ?></span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1"><?= h($donation['description']) ?></p>
                        <p class="text-sm mt-2">Donor: <span class="font-medium"><?= h($donation['donor_name']) ?></span></p>
                        <?php if ($donation['status'] !== 'completed'): ?>
                            <form method="post" class="mt-3">
                                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                <input type="hidden" name="donation_id" value="<?= h((string) $donation['id']) ?>">
                                <button class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-sm">Mark as Completed</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php render_footer(); ?>
