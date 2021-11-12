<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/DailyNote.php';

abstract class OutputBaseService
{
    protected DailyNote $dailyNote;

    public function __construct(DailyNote $dailyNote)
    {
        $this->dailyNote = $dailyNote;
    }

    protected function msgResinCount(): string
    {
        return $this->dailyNote->getCurrentResin() . ' / ' . $this->dailyNote->getMaxResin();
    }

    protected function msgResinRecovery(): string
    {
        if ($this->dailyNote->getCurrentResin() >= $this->dailyNote->getMaxResin()) {
            return 'Resin is full!';
        }
        $resinRecoveryLeft = $this->seconds2human($this->dailyNote->getResinRecoveryTime());
        $resinRecoverySeconds = $this->dailyNote->getResinRecoveryTime();
        $resinRecoveryAt = date('H:i', strtotime("+${resinRecoverySeconds} second"));
        return "${resinRecoveryLeft} left (at ${resinRecoveryAt})";
    }

    protected function msgDailyCommissionCount(): string
    {
        return $this->dailyNote->getFinishedTaskNum() . ' / ' . $this->dailyNote->getTotalTaskNum();
    }

    protected function msgGotCommissionReward(): string
    {
        return $this->dailyNote->isExtraTaskRewardReceived() ? 'Yes' : 'No';
    }

    protected function msgExpeditionCount(): string
    {
        return $this->dailyNote->getCurrentExpeditionNum() . ' / ' . $this->dailyNote->getMaxExpeditionNum();
    }

    protected function msgExpeditionRemainedTimes(int $indentCount = 0): string
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
}
