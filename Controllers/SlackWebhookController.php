<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Services/OutputFormatService.php';

class SlackWebhookController
{
    protected string $command;
    protected string $gaveSlackUserId;
    protected string $gaveText;

    public function __construct()
    {
        global $_POST;
        $this->command = $_POST['command'];
        $this->gaveSlackUserId = $_POST['user_id'];
        $this->gaveText = $_POST['text'];

        $this->echoJson();
    }

    private function getUser(): ?Config
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

        $outputFormatService = new OutputFormatService($dailyNote);
        $blocks = array_merge(
            $this->getHeadBlock($user),
            $outputFormatService->getSlackWebhookBlocks($this->command)
        );

        $data = ['response_type' => 'in_channel'];
        if ($this->command === '/genshin' || $this->command === '/resin') {
            $data['attachments'] = [
                [
                    'color' => $outputFormatService->getSlackAttachmentColor(),
                    'blocks' => $blocks,
                ]
            ];
        } else {
            $data['blocks'] = $blocks;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    private function getHeadBlock(Config $user): array
    {
        $headText = "<@{$this->gaveSlackUserId}> ";
        if ($this->gaveSlackUserId === $user->getSlackUserId()) {
            $headText .= 'Your data!';
        } else {
            $headText .= 'Data of ' . $user->getAlias();
        }
        return [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => $headText,
                ],
            ],
        ];
    }
}
