<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/DailyNote.php';

class OutputFormatService
{
    protected DailyNote $dailyNote;

    public function __construct(DailyNote $dailyNote)
    {
        $this->dailyNote = $dailyNote;
    }

    private function msgResinCount(): string
    {
        return $this->dailyNote->getCurrentResin() . ' / ' . $this->dailyNote->getMaxResin();
    }

    private function msgResinRecovery(): string
    {
        return $this->seconds2human($this->dailyNote->getResinRecoveryTime());
    }

    private function msgDailyCommissionCount(): string
    {
        return $this->dailyNote->getFinishedTaskNum() . ' / ' . $this->dailyNote->getTotalTaskNum();
    }

    private function msgGotCommissionReward(): string
    {
        return $this->dailyNote->isExtraTaskRewardReceived() ? 'Yes' : 'No';
    }

    private function msgExpeditionCount(): string
    {
        return $this->dailyNote->getCurrentExpeditionNum() . ' / ' . $this->dailyNote->getMaxExpeditionNum();
    }

    private function msgExpeditionRemainedTimes(int $indentCount = 0): string
    {
        $indents = str_repeat(' ', $indentCount);
        $texts = [];
        foreach ($this->dailyNote->getExpeditions() as $i => $expedition) {
            $texts[] = "${indents}Expedition#" . ($i + 1) . ': ' . $this->seconds2human($expedition->getRemainedTime()) . ' left';
        }
        return implode(PHP_EOL, $texts);
    }

    private function seconds2human($seconds): string
    {
        $seconds = intval($seconds);
        $hours = intval($seconds / 3600);
        $minutes = intval(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        $result = '';
        if ($hours > 0) {
            $result .= "$hours:";
        }
        $result .= str_pad(strval($minutes), 2, '0', STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad(strval($seconds), 2, '0', STR_PAD_LEFT);
        return $result;
    }

    public function getCliOutput(): string
    {
        $result = 'Resin: ' . $this->msgResinCount() . PHP_EOL;
        $result .= 'Resin Recovery: ' . $this->msgResinRecovery() . PHP_EOL;
        $result .= 'Daily Commissions: ' . $this->msgDailyCommissionCount() . PHP_EOL;
        $result .= 'Got Commission Reward: ' . $this->msgGotCommissionReward() . PHP_EOL;
        $result .= 'Expeditions: ' . $this->msgExpeditionCount() . PHP_EOL;
        $result .= $this->msgExpeditionRemainedTimes(2);
        return $result;
    }

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
