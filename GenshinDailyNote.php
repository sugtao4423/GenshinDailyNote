<?php

declare(strict_types=1);

require_once __DIR__ . '/Controllers/CliController.php';
require_once __DIR__ . '/Controllers/SlackWebhookController.php';

$isCli = (php_sapi_name() === 'cli');
$isSlackWebhook = (isset($_POST['user_id']) && isset($_POST['command']));

if ($isCli) {
    new CliController();
} else if ($isSlackWebhook) {
    new SlackWebhookController();
}
