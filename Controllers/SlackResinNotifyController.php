<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Data/User.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputSlackService.php';
require_once __DIR__ . '/../Repositories/SlackRepository.php';

class SlackResinNotifyController
{
    protected ConfigService $config;

    public function __construct()
    {
        $this->config = new ConfigService();

        $opts = getopt('au:o:n:', [
            'all-users',
            'user-alias:',
            'resin-over:',
            'not-resin-over:',
        ]);
        $isAllUsers = isset($opts['a']) ?: isset($opts['all-users']);
        $userAlias = $opts['u'] ?? @$opts['user-alias'];
        $resinOver = $opts['o'] ?? @$opts['resin-over'];
        $notResinOver = $opts['n'] ?? @$opts['not-resin-over'];

        if ((!$isAllUsers && !isset($userAlias)) ||
            !isset($resinOver) ||
            !isset($notResinOver)
        ) {
            echo 'Missing required arguments.' . PHP_EOL;
            exit(1);
        }
        if (!is_numeric($resinOver) || !is_numeric($notResinOver)) {
            echo '`resin-over` and `not-resin-over` must be numeric.' . PHP_EOL;
            exit(1);
        }

        if ($isAllUsers) {
            $this->sendAllUsers(intval($resinOver), intval($notResinOver));
        } else {
            $this->sendUser($userAlias, intval($resinOver), intval($notResinOver));
        }
    }

    private function sendAllUsers(int $resinOver, int $notResinOver): void
    {
        $users = $this->config->getUsers();
        $userCount = count($users);
        $lastIndex = $userCount - 1;
        for ($i = 0; $i < $userCount; $i++) {
            $this->sendSlack($users[$i], $resinOver, $notResinOver);
            if ($i !== $lastIndex) {
                sleep(5);
            }
        }
    }

    private function sendUser(string $userAlias, int $resinOver, int $notResinOver): void
    {
        $user = $this->config->getUserByAlias($userAlias);
        if ($user === null) {
            echo 'User not found.' . PHP_EOL;
            exit(1);
        }
        $this->sendSlack($user, $resinOver, $notResinOver);
    }

    private function sendSlack(User $user, int $resinOver, int $notResinOver): void
    {
        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo 'Failed to get daily note.' . PHP_EOL;
            exit(1);
        }

        if ($dailyNote->getCurrentResin() >= $resinOver && $dailyNote->getCurrentResin() < $notResinOver) {
            $message = (new OutputSlackService($dailyNote))->getSlackResinNotifyOutput($user);
            (new SlackRepository($this->config->getSlackWebhookUrl()))->sendJson($message);
        }
    }
}
