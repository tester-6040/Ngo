<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function require_role(string $role): void
{
    require_login();

    if ((current_user()['role'] ?? '') !== $role) {
        redirect('dashboard.php');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consume_flash(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $items;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $providedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($providedToken) || !hash_equals($sessionToken, $providedToken)) {
        flash('error', 'Your session expired. Please try again.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
    }
}

function send_admin_mail(string $subject, string $body): bool
{
    $headers = "From: noreply@ngo.local\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = mail(ADMIN_ALERT_EMAIL, $subject, $body, $headers);

    if (!$sent) {
        $line = sprintf(
            "[%s] MAIL FALLBACK | TO: %s | SUBJECT: %s | BODY: %s\n",
            date('c'),
            ADMIN_ALERT_EMAIL,
            $subject,
            $body
        );
        file_put_contents(__DIR__ . '/mail.log', $line, FILE_APPEND);
    }

    return $sent;
}
