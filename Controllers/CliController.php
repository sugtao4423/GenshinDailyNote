<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputCliService.php';

class CliController
{
    public function __construct()
    {
        global $argv;
        if (!isset($argv[1])) {
            echo 'Usage: ' . basename(__FILE__) . ' $1' . PHP_EOL;
            echo '  $1: username alias' . PHP_EOL;
            exit(1);
        }
        $this->echoCli($argv[1]);
    }

    private function echoCli(string $userAlias): void
    {
        $user = (new ConfigService())->getUserByAlias($userAlias);
        if ($user === null) {
            echo 'User not found.' . PHP_EOL;
            exit(1);
        }

        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo 'Failed to get daily note.' . PHP_EOL;
            exit(1);
        }

        $text = (new OutputCliService($dailyNote))->getCliOutput();
        echo $text . PHP_EOL;
    }
}
