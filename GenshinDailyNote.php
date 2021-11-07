<?php

declare(strict_types=1);

require_once __DIR__ . '/Controllers/CliController.php';

$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    new CliController();
}
