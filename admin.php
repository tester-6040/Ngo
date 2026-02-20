<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $donationId = (int) ($_POST['donation_id'] ?? 0);
    $orphanageId = (int) ($_POST['orphanage_id'] ?? 0);

    if ($donationId > 0 && $orphanageId > 0) {
        $status = 'assigned';
        $stmt = db()->prepare('UPDATE donations SET orphanage_id = ?, status = ? WHERE id = ?');
        $stmt->bind_param('isi', $orphanageId, $status, $donationId);
        $stmt->execute();
        flash('success', 'Donation assigned to orphanage.');
    } else {
        flash('error', 'Please select valid donation and orphanage.');
    }

    redirect('admin');
}

require_once __DIR__ . '/layout.php';
render_header('Admin Dashboard');

$donations = db()->query('SELECT d.*, u.name AS donor_name, o.name AS orphanage_name FROM donations d JOIN users u ON u.id = d.user_id LEFT JOIN users o ON o.id = d.orphanage_id ORDER BY d.created_at DESC')->fetch_all(MYSQLI_ASSOC);
$orphans = db()->query("SELECT id, name FROM users WHERE role = 'orphanage' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$pendingCount = count(array_filter($donations, static fn($item) => $item['status'] === 'pending'));
?>
<div class="space-y-5">
    <div class="grid sm:grid-cols-4 gap-3">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Total Donations</p><p class="text-2xl font-semibold"><?= h((string) count($donations)) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Pending</p><p class="text-2xl font-semibold"><?= h((string) $pendingCount) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Assigned</p><p class="text-2xl font-semibold"><?= h((string) (count($donations) - $pendingCount)) ?></p></div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-card"><p class="text-sm text-slate-500">Orphanages</p><p class="text-2xl font-semibold"><?= h((string) count($orphans)) ?></p></div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200">
            <h1 class="text-2xl font-semibold">Donation assignment queue</h1>
            <p class="text-slate-500 text-sm mt-1">Review incoming donations and assign them to the right orphanage.</p>
        </div>

        <?php if (!$donations): ?>
            <div class="p-6 text-slate-500">No donations yet.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Donation</th>
                            <th class="text-left px-4 py-3 font-semibold">Donor</th>
                            <th class="text-left px-4 py-3 font-semibold">Status</th>
                            <th class="text-left px-4 py-3 font-semibold">Orphanage</th>
                            <th class="text-left px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <?php foreach ($donations as $item): ?>
                        <tr class="align-top">
                            <td class="px-4 py-4 min-w-[260px]">
                                <p class="font-medium"><?= h($item['title']) ?> (x<?= h((string) $item['quantity']) ?>)</p>
                                <p class="text-slate-500 mt-1"><?= h($item['description']) ?></p>
                            </td>
                            <td class="px-4 py-4"><?= h($item['donor_name']) ?></td>
                            <td class="px-4 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full <?= $item['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' ?>"><?= h($item['status']) ?></span>
                            </td>
                            <td class="px-4 py-4"><?= h($item['orphanage_name'] ?? 'Not assigned') ?></td>
                            <td class="px-4 py-4 min-w-[250px]">
                                <form method="post" class="flex flex-col gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                    <input type="hidden" name="donation_id" value="<?= h((string) $item['id']) ?>">
                                    <select name="orphanage_id" class="border border-slate-300 rounded-xl px-3 py-2" required>
                                        <option value="">Assign to orphanage</option>
                                        <?php foreach ($orphans as $orphan): ?>
                                            <option value="<?= h((string) $orphan['id']) ?>"><?= h($orphan['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="bg-brand-700 hover:bg-brand-600 text-white px-4 py-2 rounded-xl">Assign donation</button>
                                </form>
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
