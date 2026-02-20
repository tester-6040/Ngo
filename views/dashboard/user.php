<?php $title = 'User Dashboard'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="grid lg:grid-cols-3 gap-6">
    <section class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">Submit Donation</h2>
        <form method="post" action="<?= rtrim($config['base_url'], '/') ?>/donations/submit" class="space-y-3">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <textarea name="description" placeholder="Dress description" rows="4" class="w-full border rounded-lg px-3 py-2" required></textarea>
            <input name="quantity" type="number" min="1" placeholder="Quantity" class="w-full border rounded-lg px-3 py-2" required>
            <textarea name="pickup_address" placeholder="Pickup address" rows="3" class="w-full border rounded-lg px-3 py-2" required></textarea>
            <button class="w-full bg-indigo-600 text-white rounded-lg py-2">Submit Donation</button>
        </form>
    </section>
    <section class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">Donation History</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b"><th>Description</th><th>Qty</th><th>Status</th><th>Orphanage</th></tr></thead>
                <tbody>
                <?php foreach ($donations as $d): ?>
                    <tr class="border-b"><td class="py-2"><?= htmlspecialchars($d['description']) ?></td><td><?= (int) $d['quantity'] ?></td><td><?= htmlspecialchars($d['status']) ?></td><td><?= htmlspecialchars($d['orphanage_name'] ?? '-') ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
