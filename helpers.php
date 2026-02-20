<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
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

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_role(string $role): void
{
    require_login();

    if ((current_user()['role'] ?? '') !== $role) {
        header('Location: dashboard.php');
        exit;
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
