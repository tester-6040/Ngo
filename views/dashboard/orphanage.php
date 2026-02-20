<?php $title = 'Orphanage Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<section class="bg-white rounded-2xl shadow-soft border border-slate-200 p-6">
    <h2 class="text-xl font-semibold mb-4">Incoming Donations</h2>
    <div class="space-y-3">
        <?php foreach ($donations as $d): ?>
            <article class="border border-slate-200 rounded-xl p-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <p class="font-semibold"><?= htmlspecialchars($d['description']) ?> (x<?= (int) $d['quantity'] ?>)</p>
                    <span class="text-xs px-2 py-1 rounded-full <?= $d['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : ($d['status'] === 'accepted' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') ?>"><?= htmlspecialchars(ucfirst($d['status'])) ?></span>
                </div>
                <p class="text-sm text-slate-600 mt-1">Donor: <?= htmlspecialchars($d['donor_name']) ?> · <?= htmlspecialchars($d['pickup_address']) ?></p>

                <?php if ($d['status'] === 'pending'): ?>
                    <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/orphanage-decision" class="flex gap-2 mt-3">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <input type="hidden" name="donation_id" value="<?= (int) $d['id'] ?>">
                        <button name="decision" value="accepted" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg">Accept</button>
                        <button name="decision" value="rejected" class="bg-rose-600 hover:bg-rose-500 text-white px-3 py-1.5 rounded-lg">Reject</button>
                    </form>
                <?php else: ?>
                    <p class="text-xs text-slate-500 mt-3">Decision already submitted. No further action required.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
