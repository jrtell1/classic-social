<?php
use Framework\Kernel;
use Symfony\Component\HttpFoundation\Request;

// Prevent worker script termination when a client connection is interrupted
ignore_user_abort(true);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/kernel.php';

$kernel = new Kernel();
$kernel->boot();

// Handler outside the loop for better performance (doing less work)
$handler = static function () use ($kernel) {
    // Called when a request is received,
    // superglobals, php://input and the like are reset
    $request = new Request(
        $_GET,
        $_POST,
        [],
        $_COOKIE,
        $_FILES,
        $_SERVER
    );

    $response = $kernel->handle($request);
    $response->send();
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; ++$nbRequests) {
    $keepRunning = \frankenphp_handle_request($handler);

    // Do something after sending the HTTP response
    $kernel->terminate();

    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();

    if (!$keepRunning) break;
}

// Cleanup
$kernel->shutdown();
