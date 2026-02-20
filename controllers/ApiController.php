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

    public function users(string $method): void
    {
        $this->ensureAuth();

        if ($method === 'GET') {
            $this->json(['data' => $this->users->all()]);
        }

        if ($method === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $role = (string) ($_POST['role'] ?? 'user');
            $id = $this->users->create($name, $email, $password, $role);
            $this->json(['id' => $id], 201);
        }

        if ($method === 'DELETE') {
            $this->json(['message' => 'Delete user endpoint placeholder'], 200);
        }

        $this->json(['error' => 'Method not allowed'], 405);
    }

    public function donations(string $method): void
    {
        $this->ensureAuth();

        if ($method === 'GET') {
            $this->json(['data' => $this->donations->allWithRelations()]);
        }

        if ($method === 'DELETE') {
            $id = (int) ($_GET['id'] ?? 0);
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

        $donationId = (int) ($_POST['donation_id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');
        $orphanageUserId = (int) ($_POST['orphanage_user_id'] ?? 0);

        if (!in_array($status, ['accepted', 'rejected'], true) || $donationId < 1 || $orphanageUserId < 1) {
            $this->json(['error' => 'Invalid payload'], 422);
        }

        $this->donations->orphanageDecision($donationId, $orphanageUserId, $status);
        $this->json(['message' => 'Updated']);
    }
}
