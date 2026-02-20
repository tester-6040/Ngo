<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    header('Location: admin.php');
    exit;
}

require_once __DIR__ . '/layout.php';
render_header('Admin Dashboard');

$donations = db()->query('SELECT d.*, u.name AS donor_name, o.name AS orphanage_name FROM donations d JOIN users u ON u.id = d.user_id LEFT JOIN users o ON o.id = d.orphanage_id ORDER BY d.created_at DESC')->fetch_all(MYSQLI_ASSOC);
$orphans = db()->query("SELECT id, name FROM users WHERE role = 'orphanage' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Admin Donation Queue</h1>
    <?php if (!$donations): ?>
        <p class="text-slate-500">No donations yet.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($donations as $item): ?>
                <article class="border rounded p-4">
                    <p class="font-semibold"><?= h($item['title']) ?> (x<?= h((string) $item['quantity']) ?>)</p>
                    <p class="text-sm text-slate-600"><?= h($item['description']) ?></p>
                    <p class="text-sm mt-1">Donor: <?= h($item['donor_name']) ?></p>
                    <p class="text-xs text-slate-500">Current status: <?= h($item['status']) ?>
                        <?php if ($item['orphanage_name']): ?>
                            | Orphanage: <?= h($item['orphanage_name']) ?>
                        <?php endif; ?>
                    </p>
                    <form method="post" class="mt-3 flex flex-wrap items-center gap-2">
                        <input type="hidden" name="donation_id" value="<?= h((string) $item['id']) ?>">
                        <select name="orphanage_id" class="border rounded px-2 py-1" required>
                            <option value="">Assign to orphanage</option>
                            <?php foreach ($orphans as $orphan): ?>
                                <option value="<?= h((string) $orphan['id']) ?>"><?= h($orphan['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="bg-blue-700 text-white px-3 py-1 rounded">Assign</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php render_footer(); ?>
