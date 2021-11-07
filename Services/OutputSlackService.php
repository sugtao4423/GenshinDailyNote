<?php

declare(strict_types=1);

require_once __DIR__ . '/OutputBaseService.php';

class OutputSlackService extends OutputBaseService
{
    public function getSlackAttachmentColor(): string
    {
        $resin = $this->dailyNote->getCurrentResin();
        if ($resin >= 140) {
            return '#A30100';
        } else if ($resin >= 120) {
            return '#DAA038';
        }
        return '#2EB886';
    }

    public function getSlackWebhookBlocks($command): array
    {
        $blocks = [];

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

    private function getSlackWebhookResinBlock(): array
    {
        return [
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'mrkdwn',
                    'text' => "*Resin:*\n" . $this->msgResinCount()
                ],
                [
                    'type' => 'mrkdwn',
                    'text' => "*Resin Recovery:*\n" . $this->msgResinRecovery()
                ]
            ],
        ];
    }

    private function getSlackWebhookDailyCommissionBlock(): array
    {
        return [
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'mrkdwn',
                    'text' => "*Daily Commissions:*\n" . $this->msgDailyCommissionCount()
                ],
                [
                    'type' => 'mrkdwn',
                    'text' => "*Got Commission Reward:*\n" . $this->msgGotCommissionReward()
                ]
            ],
        ];
    }

    private function getSlackWebhookExpeditionBlock(): array
    {
        return [
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'mrkdwn',
                    'text' => "*Expeditions:*\n" . $this->msgExpeditionCount()
                ],
                [
                    'type' => 'mrkdwn',
                    'text' => $this->msgExpeditionRemainedTimes(0)
                ]
            ],
        ];
    }
}
