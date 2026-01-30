<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function layout(string $children): string
{
    $hotReloadScripts = '';

    if (isset($_SERVER['FRANKENPHP_HOT_RELOAD'])) {
        $hotReloadScripts = <<<HTML
      <meta name="frankenphp-hot-reload:url" content="{$_SERVER['FRANKENPHP_HOT_RELOAD']}">
      <script src="https://cdn.jsdelivr.net/npm/idiomorph"></script>
      <script src="https://cdn.jsdelivr.net/npm/frankenphp-hot-reload/+esm" type="module"></script>
HTML;
    }

    return <<<HTML
        <DOCTYPE html>
        <html lang="en">
        <head>
        <title>FrankenPHP Hot Reload</title>
        {$hotReloadScripts}
        </head>
        <body>
        {$children}
        </body>
        </html>
HTML;
}
