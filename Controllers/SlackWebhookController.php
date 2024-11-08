<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/User.php';
require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputSlackService.php';

class SlackWebhookController
{
    protected string $command;
    protected string $gaveSlackUserId;
    protected string $gaveText;

    public function __construct()
    {
        $this->command = $_POST['command'];
        $this->gaveSlackUserId = $_POST['user_id'];
        $this->gaveText = $_POST['text'] ?? '';

        $this->echoJson();
    }

    private function getUser(): ?User
    {
        if (trim($this->gaveText) !== '') {
            return (new ConfigService())->getUserByAlias($this->gaveText);
        }
        return (new ConfigService())->getUserBySlackUserId($this->gaveSlackUserId);
    }

    private function echoJson(): void
    {
        $user = $this->getUser();
        if ($user === null) {
            echo 'User not found.' . PHP_EOL;
            exit(1);
        }

        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo 'Failed to get daily note.' . PHP_EOL;
            exit(1);
        }

        $text = (new OutputSlackService($dailyNote))->getSlackOutput($user, $this->gaveSlackUserId, $this->command);
        header('Content-Type: application/json; charset=utf-8');
        echo $text;
    }
}
