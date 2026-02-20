<?php

declare(strict_types=1);

namespace Models;

use Core\BaseModel;

final class Donation extends BaseModel
{
    public function create(int $userId, string $description, int $quantity, string $pickupAddress): int
    {
        return $this->insert(
            'INSERT INTO donations (user_id, description, quantity, pickup_address, status, orphanage_user_id, created_at, updated_at) VALUES (:user_id,:description,:quantity,:pickup_address,:status,NULL,NOW(),NOW())',
            [
                'user_id' => $userId,
                'description' => $description,
                'quantity' => $quantity,
                'pickup_address' => $pickupAddress,
                'status' => 'pending',
            ]
        );
    }

    public function allWithRelations(): array
    {
        return $this->select(
            'SELECT d.*, u.name AS donor_name, u.email AS donor_email, o.name AS orphanage_name
             FROM donations d
             JOIN users u ON u.id = d.user_id
             LEFT JOIN users o ON o.id = d.orphanage_user_id
             ORDER BY d.id DESC'
        );
    }

    public function byDonor(int $userId): array
    {
        return $this->select(
            'SELECT d.*, o.name AS orphanage_name
             FROM donations d
             LEFT JOIN users o ON o.id = d.orphanage_user_id
             WHERE d.user_id = :user_id ORDER BY d.id DESC',
            ['user_id' => $userId]
        );
    }

    public function byOrphanage(int $orphanageUserId): array
    {
        return $this->select(
            'SELECT d.*, u.name AS donor_name, u.email AS donor_email
             FROM donations d
             JOIN users u ON u.id = d.user_id
             WHERE d.orphanage_user_id = :orphanage_user_id
             ORDER BY d.id DESC',
            ['orphanage_user_id' => $orphanageUserId]
        );
    }

    public function assignAndApprove(int $donationId, int $orphanageUserId): int
    {
        return $this->update(
            'UPDATE donations
             SET orphanage_user_id = :orphanage_user_id, updated_at = NOW()
             WHERE id = :id AND status = :status',
            [
                'orphanage_user_id' => $orphanageUserId,
                'id' => $donationId,
                'status' => 'pending',
            ]
        );
    }

    public function orphanageDecision(int $donationId, int $orphanageUserId, string $status): int
    {
        return $this->update(
            'UPDATE donations
             SET status = :status, updated_at = NOW()
             WHERE id = :id AND orphanage_user_id = :orphanage_user_id AND status = :pending_status',
            [
                'status' => $status,
                'id' => $donationId,
                'orphanage_user_id' => $orphanageUserId,
                'pending_status' => 'pending',
            ]
        );
    }

    public function updateStatusById(int $id, string $status): int
    {
        return $this->update(
            'UPDATE donations SET status = :status, updated_at = NOW() WHERE id = :id',
            ['id' => $id, 'status' => $status]
        );
    }

    public function deleteById(int $id): int
    {
        return $this->delete('DELETE FROM donations WHERE id = :id', ['id' => $id]);
    }

    public function find(int $id): ?array
    {
        $rows = $this->select('SELECT * FROM donations WHERE id = :id LIMIT 1', ['id' => $id]);

        return $rows[0] ?? null;
    }
}
