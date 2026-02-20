<?php

declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $config = $this->config;
        require __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        $base = rtrim($this->config['base_url'], '/');
        header('Location: ' . $base . $path);
        exit;
    }

    protected function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
