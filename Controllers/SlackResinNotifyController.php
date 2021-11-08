<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputSlackService.php';
require_once __DIR__ . '/../Repositories/SlackRepository.php';

class SlackResinNotifyController
{
    public function __construct()
    {
        $opts = getopt('u:o:n:', [
            'user-alias:',
            'resin-over:',
            'not-resin-over:',
        ]);
        $userAlias = $opts['u'] ?? @$opts['user-alias'];
        $resinOver = $opts['o'] ?? @$opts['resin-over'];
        $notResinOver = $opts['n'] ?? @$opts['not-resin-over'];

        if (!isset($userAlias) || !isset($resinOver) || !isset($notResinOver)) {
            echo 'Missing required arguments.' . PHP_EOL;
            exit(1);
        }
        if (!is_numeric($resinOver) || !is_numeric($notResinOver)) {
            echo '`resin-over` and `not-resin-over` must be numeric.' . PHP_EOL;
            exit(1);
        }

        $this->sendSlack($userAlias, intval($resinOver), intval($notResinOver));
    }

    private function sendSlack(string $userAlias, int $resinOver, int $notResinOver): void
    {
        $config = new ConfigService();

        $user = $config->getUserByAlias($userAlias);
        if ($user === null) {
            echo 'User not found.' . PHP_EOL;
            exit(1);
        }

        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo 'Failed to get daily note.' . PHP_EOL;
            exit(1);
        }

        if ($dailyNote->getCurrentResin() >= $resinOver && $dailyNote->getCurrentResin() < $notResinOver) {
            $message = (new OutputSlackService($dailyNote))->getSlackResinNotifyOutput($user);
            (new SlackRepository($config->getSlackWebhookUrl()))->sendJson($message);
        }
    }
}
