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
}