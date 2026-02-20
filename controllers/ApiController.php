<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Session;
use Models\Donation;
use Models\User;

final class ApiController extends Controller
{
    public function __construct(array $config, private User $users, private Donation $donations)
    {
        parent::__construct($config);
    }

    private function ensureAuth(): void
    {
        if (!Session::user()) {
            $this->json(['error' => 'Unauthorized'], 401);
        }
    }

    private function body(): array
    {
        $raw = file_get_contents('php://input') ?: '';
        $json = json_decode($raw, true);

        if (is_array($json)) {
            return $json;
        }

        return $_POST;
    }

    public function users(string $method): void
    {
        $this->ensureAuth();

        if ($method === 'GET') {
            $this->json(['data' => $this->users->all()]);
        }

        if ($method === 'POST') {
            $payload = $this->body();
            $name = trim((string) ($payload['name'] ?? ''));
            $email = trim((string) ($payload['email'] ?? ''));
            $password = (string) ($payload['password'] ?? '');
            $role = (string) ($payload['role'] ?? 'user');

            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || !in_array($role, ['admin', 'user', 'orphanage'], true)) {
                $this->json(['error' => 'Invalid payload'], 422);
            }

            $id = $this->users->create($name, $email, $password, $role);
            $this->json(['id' => $id], 201);
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            $payload = $this->body();
            $id = (int) ($payload['id'] ?? 0);
            $name = trim((string) ($payload['name'] ?? ''));
            $email = trim((string) ($payload['email'] ?? ''));
            $role = (string) ($payload['role'] ?? '');

            if ($id < 1 || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'user', 'orphanage'], true)) {
                $this->json(['error' => 'Invalid payload'], 422);
            }

            $this->users->updateById($id, $name, $email, $role);
            $this->json(['message' => 'Updated']);
        }

        if ($method === 'DELETE') {
            $payload = $this->body();
            $id = (int) ($payload['id'] ?? ($_GET['id'] ?? 0));
            if ($id < 1) {
                $this->json(['error' => 'Invalid user id'], 422);
            }
            $this->users->deleteById($id);
            $this->json(['message' => 'Deleted']);
        }

        $this->json(['error' => 'Method not allowed'], 405);
    }

    public function donations(string $method): void
    {
        $this->ensureAuth();

        if ($method === 'GET') {
            $this->json(['data' => $this->donations->allWithRelations()]);
        }

        if ($method === 'POST') {
            $payload = $this->body();
            $userId = (int) ($payload['user_id'] ?? 0);
            $description = trim((string) ($payload['description'] ?? ''));
            $quantity = (int) ($payload['quantity'] ?? 0);
            $pickupAddress = trim((string) ($payload['pickup_address'] ?? ''));

            if ($userId < 1 || $description === '' || $quantity < 1 || $pickupAddress === '') {
                $this->json(['error' => 'Invalid payload'], 422);
            }

            $id = $this->donations->create($userId, $description, $quantity, $pickupAddress);
            $this->json(['id' => $id], 201);
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            $payload = $this->body();
            $id = (int) ($payload['id'] ?? 0);
            $status = (string) ($payload['status'] ?? '');
            if ($id < 1 || !in_array($status, ['pending', 'accepted', 'rejected'], true)) {
                $this->json(['error' => 'Invalid payload'], 422);
            }
            $this->donations->updateStatusById($id, $status);
            $this->json(['message' => 'Updated']);
        }

        if ($method === 'DELETE') {
            $payload = $this->body();
            $id = (int) ($payload['id'] ?? ($_GET['id'] ?? 0));
            if ($id < 1) {
                $this->json(['error' => 'Invalid donation id'], 422);
            }
            $this->donations->deleteById($id);
            $this->json(['message' => 'Deleted']);
        }

        $this->json(['error' => 'Method not allowed'], 405);
    }

    public function orphanageAction(string $method): void
    {
        $this->ensureAuth();

        if ($method !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }

        $payload = $this->body();
        $donationId = (int) ($payload['donation_id'] ?? 0);
        $status = (string) ($payload['status'] ?? '');
        $orphanageUserId = (int) ($payload['orphanage_user_id'] ?? 0);

        if (!in_array($status, ['accepted', 'rejected'], true) || $donationId < 1 || $orphanageUserId < 1) {
            $this->json(['error' => 'Invalid payload'], 422);
        }

        $this->donations->orphanageDecision($donationId, $orphanageUserId, $status);
        $this->json(['message' => 'Updated']);
    }
}
