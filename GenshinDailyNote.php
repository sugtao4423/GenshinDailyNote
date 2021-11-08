<?php

declare(strict_types=1);

require_once __DIR__ . '/Services/ConfigService.php';
require_once __DIR__ . '/Controllers/SlackResinNotifyController.php';
require_once __DIR__ . '/Controllers/CliController.php';
require_once __DIR__ . '/Controllers/SlackWebhookController.php';

date_default_timezone_set((new ConfigService())->getTimezone());

$opts = getopt('s', ['send-slack']);
$isSlackNotify = (isset($opts['s']) || isset($opts['send-slack']));
$isCli = (php_sapi_name() === 'cli');
$isSlackWebhook = (isset($_POST['user_id']) && isset($_POST['command']));

if ($isSlackNotify) {
    new SlackResinNotifyController();
} else if ($isCli) {
    new CliController();
} else if ($isSlackWebhook) {
    new SlackWebhookController();
}
