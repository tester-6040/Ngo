<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_role('orphanage');

require_once __DIR__ . '/layout.php';
render_header('Orphanage Dashboard');

$orphanageId = current_user()['id'];
$stmt = db()->prepare('SELECT d.*, u.name AS donor_name FROM donations d JOIN users u ON u.id = d.user_id WHERE d.orphanage_id = ? ORDER BY d.created_at DESC');
$stmt->bind_param('i', $orphanageId);
$stmt->execute();
$donations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Assigned Donations</h1>
    <?php if (!$donations): ?>
        <p class="text-slate-500">No donations assigned yet.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($donations as $donation): ?>
                <article class="border rounded p-3">
                    <p class="font-semibold"><?= h($donation['title']) ?> (x<?= h((string) $donation['quantity']) ?>)</p>
                    <p class="text-sm text-slate-600"><?= h($donation['description']) ?></p>
                    <p class="text-sm">Donor: <?= h($donation['donor_name']) ?></p>
                    <p class="text-xs text-slate-500">Status: <?= h($donation['status']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php render_footer(); ?>
