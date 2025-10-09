<?php
// Lightweight deploy helper to clear Laravel caches via HTTP.
// Remove this file after use for security.

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$base = dirname(__DIR__);
$result = [
    'status' => 'error',
    'message' => null,
    'steps' => [],
];

try {
    // Basic sanity checks
    if (!is_dir($base . '/vendor')) {
        throw new RuntimeException('Missing vendor directory. Upload vendor/ or run composer install.');
    }

    // Autoload and bootstrap the app
    require $base . '/vendor/autoload.php';

    $app = require $base . '/bootstrap/app.php';
    /** @var \Illuminate\Contracts\Console\Kernel $kernel */
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

    // Bootstrap the application for console commands
    $kernel->bootstrap();

    // Run cache clearing commands
    $exitCodes = [];
    $commands = [
        'optimize:clear',
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'event:clear',
    ];

    foreach ($commands as $cmd) {
        $code = $kernel->call($cmd);
        $exitCodes[$cmd] = $code;
        $result['steps'][] = [
            'command' => $cmd,
            'exit_code' => $code,
            'output' => $kernel->output(),
        ];
    }

    $allOk = array_reduce($exitCodes, fn($ok, $code) => $ok && ($code === 0), true);
    if ($allOk) {
        $result['status'] = 'ok';
        $result['message'] = 'All Laravel caches cleared successfully.';
    } else {
        $result['status'] = 'partial';
        $result['message'] = 'Some cache clear commands reported non-zero exit codes.';
    }
} catch (Throwable $e) {
    http_response_code(500);
    $result['status'] = 'error';
    $result['message'] = $e->getMessage();
    $result['exception'] = [
        'type' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ];
}

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

