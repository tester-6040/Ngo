<?php

declare(strict_types=1);

namespace Core;

final class Url
{
    public static function baseUrl(string $configuredBaseUrl = ''): string
    {
        if ($configuredBaseUrl !== '') {
            return rtrim($configuredBaseUrl, '/');
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = str_replace('\\', '/', dirname((string) $scriptName));

        if ($dir === '/' || $dir === '.' || $dir === '\\') {
            return '';
        }

        return '/' . trim($dir, '/');
    }

    public static function appPath(string $requestPath, string $baseUrl): string
    {
        if ($baseUrl !== '' && str_starts_with($requestPath, $baseUrl)) {
            $requestPath = substr($requestPath, strlen($baseUrl));
        }

        $requestPath = $requestPath === '' ? '/' : $requestPath;

        return $requestPath;
    }
}
