<?php $title = 'Admin Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="grid lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 bg-white rounded-2xl shadow-soft border border-slate-200 p-6">
        <h2 class="text-xl font-semibold mb-4">Donation Approval Queue</h2>
        <div class="space-y-3">
            <?php foreach ($donations as $d): ?>
                <article class="border border-slate-200 rounded-xl p-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-semibold"><?= htmlspecialchars($d['description']) ?> (x<?= (int) $d['quantity'] ?>)</p>
                        <span class="text-xs px-2 py-1 rounded-full <?= $d['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : ($d['status'] === 'accepted' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') ?>"><?= htmlspecialchars($d['status']) ?></span>
                    </div>
                    <p class="text-sm text-slate-600 mt-1">Donor: <?= htmlspecialchars($d['donor_name']) ?> · <?= htmlspecialchars($d['pickup_address']) ?></p>
                    <p class="text-sm text-slate-500">Assigned Orphanage: <?= htmlspecialchars($d['orphanage_name'] ?? 'Not assigned') ?></p>
                    <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/admin-assign" class="mt-3 flex flex-wrap gap-2 items-center">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <input type="hidden" name="donation_id" value="<?= (int) $d['id'] ?>">
                        <select name="orphanage_user_id" class="border rounded-lg px-3 py-2" required>
                            <option value="">Assign Orphanage</option>
                            <?php foreach ($orphans as $o): ?>
                                <option value="<?= (int) $o['id'] ?>"><?= htmlspecialchars($o['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg">Approve + Assign</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bg-white rounded-2xl shadow-soft border border-slate-200 p-6">
        <h2 class="text-xl font-semibold mb-3">System Users</h2>
        <ul class="text-sm divide-y">
            <?php foreach ($users as $u): ?>
                <li class="py-2 flex justify-between">
                    <span><?= htmlspecialchars($u['name']) ?></span>
                    <span class="text-slate-500"><?= htmlspecialchars($u['role']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
