<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Csrf;
use Core\Session;
use Models\User;

final class AuthController extends Controller
{
    private User $users;

    public function __construct(array $config, User $users)
    {
        parent::__construct($config);
        $this->users = $users;
    }

    public function showLogin(): void
    {
        $this->view('auth/login', ['csrf' => Csrf::token()]);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['csrf' => Csrf::token()]);
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? '');

        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash']) || $user['role'] !== $role) {
            Session::set('flash_error', 'Invalid credentials.');
            $this->redirect('/login');
        }

        Session::regenerate();
        Session::set('auth_user', [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        $this->redirect('/dashboard');
    }

    public function register(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            $this->redirect('/register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? 'user');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || !in_array($role, ['user', 'orphanage'], true)) {
            Session::set('flash_error', 'Invalid registration data.');
            $this->redirect('/register');
        }

        if ($this->users->findByEmail($email)) {
            Session::set('flash_error', 'Email already exists.');
            $this->redirect('/register');
        }

        $this->users->create($name, $email, $password, $role);
        Session::set('flash_success', 'Registration successful. Please login.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->redirect('/');
        }

        Session::destroy();
        $this->redirect('/');
    }
}
