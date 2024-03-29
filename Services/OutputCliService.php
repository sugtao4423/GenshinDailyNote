<?php

declare(strict_types=1);

require_once __DIR__ . '/OutputBaseService.php';

class OutputCliService extends OutputBaseService
{
    public function getCliOutput(): string
    {
        $result = 'Resin: ' . $this->msgResinCount() . PHP_EOL;
        $result .= 'Resin Recovery: ' . $this->msgResinRecovery() . PHP_EOL;
        $result .= 'Daily Commissions: ' . $this->msgDailyCommissionCount() . PHP_EOL;
        $result .= 'Got Commission Reward: ' . $this->msgGotCommissionReward() . PHP_EOL;
        $result .= 'Expeditions: ' . $this->msgExpeditionCount() . PHP_EOL;
        $result .= $this->msgExpeditionRemainedTimes(2) . PHP_EOL;
        $result .= 'Home Coin: ' . $this->msgHomeCoinCount() . PHP_EOL;
        $result .= 'Home Coin Recovery: ' . $this->msgHomeCoinRecovery();
        return $result;
    }
}
