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

    redirect('orphanage');
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
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Assigned</p><p class="text-2xl font-semibold"><?= h((string) count($donations)) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Completed</p><p class="text-2xl font-semibold"><?= h((string) $completed) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">In Progress</p><p class="text-2xl font-semibold"><?= h((string) (count($donations) - $completed)) ?></p></div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200">
            <h1 class="text-2xl font-semibold">Assigned donations</h1>
            <p class="text-slate-500 text-sm mt-1">Manage and complete donations allocated to your orphanage.</p>
        </div>

        <?php if (!$donations): ?>
            <div class="p-6 text-slate-500">No donations assigned yet.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Donation</th>
                            <th class="text-left px-4 py-3 font-semibold">Donor</th>
                            <th class="text-left px-4 py-3 font-semibold">Status</th>
                            <th class="text-left px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <?php foreach ($donations as $donation): ?>
                        <tr>
                            <td class="px-4 py-4 min-w-[300px]"><p class="font-medium"><?= h($donation['title']) ?> (x<?= h((string) $donation['quantity']) ?>)</p><p class="text-slate-500 mt-1"><?= h($donation['description']) ?></p></td>
                            <td class="px-4 py-4"><?= h($donation['donor_name']) ?></td>
                            <td class="px-4 py-4"><span class="text-xs px-2.5 py-1 rounded-full <?= $donation['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' ?>"><?= h($donation['status']) ?></span></td>
                            <td class="px-4 py-4 min-w-[180px]">
                                <?php if ($donation['status'] !== 'completed'): ?>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                        <input type="hidden" name="donation_id" value="<?= h((string) $donation['id']) ?>">
                                        <button class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-sm">Mark complete</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-slate-400">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php render_footer(); ?>
