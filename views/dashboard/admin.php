<?php $title = 'Admin Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="grid lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">All Donations</h2>
        <div class="space-y-3">
            <?php foreach ($donations as $d): ?>
                <div class="border rounded-lg p-4">
                    <p class="font-medium"><?= htmlspecialchars($d['description']) ?> (x<?= (int) $d['quantity'] ?>)</p>
                    <p class="text-sm text-slate-600">Donor: <?= htmlspecialchars($d['donor_name']) ?> | <?= htmlspecialchars($d['pickup_address']) ?></p>
                    <p class="text-sm">Status: <strong><?= htmlspecialchars($d['status']) ?></strong> | Orphanage: <?= htmlspecialchars($d['orphanage_name'] ?? '-') ?></p>
                    <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/admin-assign" class="mt-2 flex gap-2 items-center">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <input type="hidden" name="donation_id" value="<?= (int) $d['id'] ?>">
                        <select name="orphanage_user_id" class="border rounded px-2 py-1" required>
                            <option value="">Assign Orphanage</option>
                            <?php foreach ($orphans as $o): ?>
                                <option value="<?= (int) $o['id'] ?>"><?= htmlspecialchars($o['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded">Approve + Assign</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">System Users</h2>
        <ul class="text-sm space-y-2">
            <?php foreach ($users as $u): ?>
                <li class="border-b pb-2"><?= htmlspecialchars($u['name']) ?> <span class="text-slate-500">(<?= htmlspecialchars($u['role']) ?>)</span></li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
