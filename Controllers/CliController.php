<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ConfigService.php';
require_once __DIR__ . '/../Services/DailyNoteService.php';
require_once __DIR__ . '/../Commons/Utils.php';

class CliController
{
    public function __construct()
    {
        global $argv;
        if (!isset($argv[1])) {
            echo 'Usage: ' . basename(__FILE__) . " $1\n";
            echo "  $1: username alias\n";
            exit(1);
        }
        $this->echoCli($argv[1]);
    }

    private function echoCli(string $userAlias): void
    {
        $user = (new ConfigService())->getUserByAlias($userAlias);
        if ($user === null) {
            echo "User not found.\n";
            exit(1);
        }

        $dailyNote = (new DailyNoteService())->getDailyNote($user);
        if ($dailyNote === null) {
            echo "Failed to get daily note.\n";
            exit(1);
        }

        $resins = Utils::currentSmax($dailyNote->getCurrentResin(), $dailyNote->getMaxResin());
        $resinRecovery = Utils::seconds2human($dailyNote->getResinRecoveryTime());
        $dailyCommissions = Utils::currentSmax($dailyNote->getFinishedTaskNum(), $dailyNote->getTotalTaskNum());
        $gotCommissionReward = $dailyNote->isExtraTaskRewardReceived() ? 'Yes' : 'No';
        $expeditions = Utils::currentSmax($dailyNote->getCurrentExpeditionNum(), $dailyNote->getMaxExpeditionNum());

        echo "Resin: $resins\n";
        echo "Resin Recovery: $resinRecovery\n";
        echo "Daily Commissions: $dailyCommissions\n";
        echo "Got Commission Reward: $gotCommissionReward\n";
        echo "Expeditions: $expeditions\n";

        foreach ($dailyNote->getExpeditions() as $i => $expedition) {
            echo '  Expedition#' . ($i + 1) . ": ";
            echo Utils::seconds2human($expedition->getRemainedTime()) . " left\n";
        }
    }
}
