<?php $title = 'Orphanage Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-xl font-semibold mb-3">Incoming Donations</h2>
    <div class="space-y-3">
        <?php foreach ($donations as $d): ?>
            <div class="border rounded-lg p-4">
                <p class="font-medium"><?= htmlspecialchars($d['description']) ?> (x<?= (int) $d['quantity'] ?>)</p>
                <p class="text-sm text-slate-600">Donor: <?= htmlspecialchars($d['donor_name']) ?> | <?= htmlspecialchars($d['pickup_address']) ?></p>
                <p class="text-sm">Status: <strong><?= htmlspecialchars($d['status']) ?></strong></p>
                <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/orphanage-decision" class="flex gap-2 mt-2">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <input type="hidden" name="donation_id" value="<?= (int) $d['id'] ?>">
                    <button name="decision" value="accepted" class="bg-emerald-600 text-white px-3 py-1 rounded">Accept</button>
                    <button name="decision" value="rejected" class="bg-rose-600 text-white px-3 py-1 rounded">Reject</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
