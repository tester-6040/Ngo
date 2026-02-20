<?php $title = 'User Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="grid lg:grid-cols-3 gap-6">
    <section class="bg-white rounded-2xl shadow-soft border border-slate-200 p-6">
        <h2 class="text-xl font-semibold mb-1">Submit Donation</h2>
        <p class="text-slate-500 text-sm mb-4">Share used dress details for pickup coordination.</p>
        <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/submit" class="space-y-3">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <textarea name="description" placeholder="Dress description" rows="4" class="w-full border rounded-xl px-3 py-2.5" required></textarea>
            <input name="quantity" type="number" min="1" placeholder="Quantity" class="w-full border rounded-xl px-3 py-2.5" required>
            <textarea name="pickup_address" placeholder="Pickup address" rows="3" class="w-full border rounded-xl px-3 py-2.5" required></textarea>
            <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl py-2.5">Submit Donation</button>
        </form>
    </section>

    <section class="lg:col-span-2 bg-white rounded-2xl shadow-soft border border-slate-200 p-6">
        <h2 class="text-xl font-semibold mb-4">Donation History</h2>
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="p-3">Description</th>
                        <th class="p-3">Qty</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Orphanage</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($donations as $d): ?>
                    <tr class="border-t">
                        <td class="p-3"><?= htmlspecialchars($d['description']) ?></td>
                        <td class="p-3"><?= (int) $d['quantity'] ?></td>
                        <td class="p-3"><span class="px-2 py-1 rounded-full text-xs <?= $d['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : ($d['status'] === 'accepted' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') ?>"><?= htmlspecialchars(ucfirst($d['status'])) ?></span></td>
                        <td class="p-3"><?= htmlspecialchars($d['orphanage_name'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
