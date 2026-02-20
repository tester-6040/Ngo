<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Csrf;
use Core\Session;
use Models\Donation;
use Models\User;

final class DashboardController extends Controller
{
    public function __construct(array $config, private Donation $donations, private User $users)
    {
        parent::__construct($config);
    }

    public function home(): void
    {
        $user = Session::user();
        if (!$user) {
            $this->redirect('/login');
        }

        $csrf = Csrf::token();
        if ($user['role'] === 'admin') {
            $this->view('dashboard/admin', [
                'user' => $user,
                'csrf' => $csrf,
                'donations' => $this->donations->allWithRelations(),
                'orphans' => $this->users->allByRole('orphanage'),
                'users' => $this->users->all(),
            ]);
            return;
        }

        if ($user['role'] === 'orphanage') {
            $this->view('dashboard/orphanage', [
                'user' => $user,
                'csrf' => $csrf,
                'donations' => $this->donations->byOrphanage((int) $user['id']),
            ]);
            return;
        }

        $this->view('dashboard/user', [
            'user' => $user,
            'csrf' => $csrf,
            'donations' => $this->donations->byDonor((int) $user['id']),
        ]);
    }
}
