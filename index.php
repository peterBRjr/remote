<?php

// Ativar exibição de erros PHP para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!file_exists(__DIR__ . '/.env')) {
    echo "<div style='font-family:sans-serif; padding:20px; background:#fee2e2; color:#991b1b; border-radius:8px; margin:20px;'>";
    echo "<h2>⚠️ Arquivo .env não encontrado!</h2>";
    echo "<p>O arquivo <strong>.env</strong> não está presente na pasta <code>htdocs</code>.</p>";
    echo "<p>Por favor, envie o arquivo <strong>.env</strong> (ou renomeie o <code>.env.infinityfree</code> para <code>.env</code>) dentro da pasta <code>htdocs</code>.</p>";
    echo "</div>";
    exit;
}

try {
    define('LARAVEL_START', microtime(true));

    if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
        require $maintenance;
    }

    require __DIR__.'/vendor/autoload.php';

    $app = require_once __DIR__.'/bootstrap/app.php';

    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    echo "<div style='font-family:sans-serif; padding:20px; background:#fef2f2; border:1px solid #fca5a5; border-radius:8px; margin:20px;'>";
    echo "<h2 style='color:#dc2626;'>⚠️ Erro no Laravel:</h2>";
    echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . " na linha <strong>" . $e->getLine() . "</strong></p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='background:#f1f5f9; padding:15px; border-radius:5px; overflow-x:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
