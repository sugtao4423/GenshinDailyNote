<?php

declare(strict_types=1);

require_once __DIR__ . '/OutputBaseService.php';
require_once __DIR__ . '/../Data/Config.php';

class OutputSlackService extends OutputBaseService
{
    public function getSlackOutput(Config $user, string $gaveSlackUserId, string $command): string
    {
        $blocks = $this->getSlackWebhookBlocks($user, $gaveSlackUserId, $command);

        $data = ['response_type' => 'in_channel'];
        if ($command === '/genshin' || $command === '/resin') {
            $data['attachments'] = [
                [
                    'color' => $this->getSlackAttachmentColor(),
                    'blocks' => $blocks,
                ]
            ];
        } else {
            $data['blocks'] = $blocks;
        }

        return json_encode($data);
    }

    private function getSlackAttachmentColor(): string
    {
        $resin = $this->dailyNote->getCurrentResin();
        if ($resin >= 140) {
            return '#A30100';
        } else if ($resin >= 120) {
            return '#DAA038';
        }
        return '#2EB886';
    }

    private function getSlackWebhookBlocks(Config $user, string $gaveSlackUserId, string $command): array
    {
        $headText = "<@${gaveSlackUserId}> ";
        if ($gaveSlackUserId === $user->getSlackUserId()) {
            $headText .= 'Your data!';
        } else {
            $headText .= 'Data of ' . $user->getAlias();
        }

        $blocks = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => $headText,
                ],
            ],
        ];

        if ($command === '/genshin' || $command === '/resin') {
            $blocks[] = $this->getSlackWebhookResinBlock();
        }
        if ($command === '/genshin' || $command === '/daily') {
            $blocks[] = $this->getSlackWebhookDailyCommissionBlock();
        }
        if ($command === '/genshin' || $command === '/expedition') {
            $blocks[] = $this->getSlackWebhookExpeditionBlock();
        }

        return $blocks;
    }

    private function createSection(string $text1, string $text2): array
    {
        return [
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'mrkdwn',
                    'text' => $text1,
                ],
                [
                    'type' => 'mrkdwn',
                    'text' => $text2,
                ]
            ],
        ];
    }

    private function getSlackWebhookResinBlock(): array
    {
        return $this->createSection(
            "*Resin:*\n" . $this->msgResinCount(),
            "*Resin Recovery:*\n" . $this->msgResinRecovery()
        );
    }

    private function getSlackWebhookDailyCommissionBlock(): array
    {
        return $this->createSection(
            "*Daily Commissions:*\n" . $this->msgDailyCommissionCount(),
            "*Got Commission Reward:*\n" . $this->msgGotCommissionReward()
        );
    }

    private function getSlackWebhookExpeditionBlock(): array
    {
        return $this->createSection(
            "*Expeditions:*\n" . $this->msgExpeditionCount(),
            $this->msgExpeditionRemainedTimes(0)
        );
    }
}
