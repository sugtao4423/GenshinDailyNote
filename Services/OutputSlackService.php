<?php

declare(strict_types=1);

require_once __DIR__ . '/OutputBaseService.php';
require_once __DIR__ . '/../Data/User.php';

class OutputSlackService extends OutputBaseService
{
    public function getSlackOutput(User $user, string $gaveSlackUserId, string $command): string
    {
        $headText = "<@{$gaveSlackUserId}> ";
        if ($gaveSlackUserId === $user->getSlackUserId()) {
            $headText .= 'Your data!';
        } else {
            $headText .= 'Data of ' . $user->getAlias();
        }

        $blocks = $this->getSlackWebhookBlocks($command);

        $data = ['response_type' => 'in_channel'];
        if ($command === '/genshin' || $command === '/resin') {
            $data['text'] = $headText;
            $data['attachments'] = [
                [
                    'color' => $this->getSlackAttachmentColor(),
                    'blocks' => $blocks,
                ]
            ];
        } else {
            $headBlock = [$this->createSection($headText)];
            $data['blocks'] = array_merge($headBlock, $blocks);
        }

        return json_encode($data);
    }

    public function getSlackResinNotifyOutput(User $user): string
    {
        $data = [
            'response_type' => 'in_channel',
            'text' => "<@{$user->getSlackUserId()}> Check your resin!",
            'attachments' => [
                [
                    'color' => $this->getSlackAttachmentColor(),
                    'blocks' => [
                        $this->getSlackWebhookResinBlock(),
                    ],
                ],
            ],
        ];
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

    private function getSlackWebhookBlocks(string $command): array
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
        if ($command === '/genshin' || $command === '/home') {
            $blocks[] = $this->getSlackWebhookHomeCoinBlock();
        }

        return $blocks;
    }

    private function createSection(string $text): array
    {
        return [
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => $text,
            ],
        ];
    }

    private function createFiledsSection(string $text1, string $text2): array
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
        return $this->createFiledsSection(
            "*Resin:*\n" . $this->msgResinCount(),
            "*Resin Recovery:*\n" . $this->msgResinRecovery(),
        );
    }

    private function getSlackWebhookDailyCommissionBlock(): array
    {
        return $this->createFiledsSection(
            "*Daily Commissions:*\n" . $this->msgDailyCommissionCount(),
            "*Got Commission Reward:*\n" . $this->msgGotCommissionReward()
        );
    }

    private function getSlackWebhookExpeditionBlock(): array
    {
        return $this->createFiledsSection(
            "*Expeditions:*\n" . $this->msgExpeditionCount(),
            $this->msgExpeditionRemainedTimes(0)
        );
    }

    private function getSlackWebhookHomeCoinBlock(): array
    {
        return $this->createFiledsSection(
            "*Home Coin:*\n" . $this->msgHomeCoinCount(),
            "*Home Coin Recovery:*\n" . $this->msgHomeCoinRecovery()
        );
    }
}
