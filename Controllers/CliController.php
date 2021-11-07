<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputFormatService.php';

class CliController
{
    public function __construct()
    {
        global $argv;
        if (!isset($argv[1])) {
            echo 'Usage: ' . basename(__FILE__) . " $1\n";
            echo "  $1: username alias\n";
            exit(1);
        }
        $this->echoCli($argv[1]);
    }

    private function echoCli(string $userAlias): void
    {
        $user = (new ConfigService())->getUserByAlias($userAlias);
        if ($user === null) {
            echo "User not found.\n";
            exit(1);
        }

        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo "Failed to get daily note.\n";
            exit(1);
        }

        $text = (new OutputFormatService($dailyNote))->getCliOutput();
        echo $text . PHP_EOL;
    }
}
