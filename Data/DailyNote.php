<?php

declare(strict_types=1);

require_once __DIR__ . '/Expedition.php';

class DailyNote
{
    protected int $currentResin;
    protected int $maxResin;
    protected string $resinRecoveryTime;
    protected int $finishedTaskNum;
    protected int $totalTaskNum;
    protected bool $isExtraTaskRewardReceived;
    protected int $remainResinDiscountNum;
    protected int $resinDiscountNumLimit;
    protected int $currentExpeditionNum;
    protected int $maxExpeditionNum;
    protected int $currentHomeCoin;
    protected int $maxHomeCoin;
    protected string $homeCoinRecoveryTime;

    /**
     * @var Expedition[]
     */
    protected array $expeditions;

    public function __construct(array $data)
    {
        $this->currentResin = $data['current_resin'];
        $this->maxResin = $data['max_resin'];
        $this->resinRecoveryTime = $data['resin_recovery_time'];
        $this->finishedTaskNum = $data['finished_task_num'];
        $this->totalTaskNum = $data['total_task_num'];
        $this->isExtraTaskRewardReceived = $data['is_extra_task_reward_received'];
        $this->remainResinDiscountNum = $data['remain_resin_discount_num'];
        $this->resinDiscountNumLimit = $data['resin_discount_num_limit'];
        $this->currentExpeditionNum = $data['current_expedition_num'];
        $this->maxExpeditionNum = $data['max_expedition_num'];
        $this->expeditions = array_map(function ($data) {
            return new Expedition($data);
        }, $data['expeditions']);
        $this->currentHomeCoin = $data['current_home_coin'];
        $this->maxHomeCoin = $data['max_home_coin'];
        $this->homeCoinRecoveryTime = $data['home_coin_recovery_time'];
    }

    public function getCurrentResin(): int
    {
        return $this->currentResin;
    }

    public function getMaxResin(): int
    {
        return $this->maxResin;
    }

    public function getResinRecoveryTime(): string
    {
        return $this->resinRecoveryTime;
    }

    public function getFinishedTaskNum(): int
    {
        return $this->finishedTaskNum;
    }

    public function getTotalTaskNum(): int
    {
        return $this->totalTaskNum;
    }

    public function isExtraTaskRewardReceived(): bool
    {
        return $this->isExtraTaskRewardReceived;
    }

    public function getRemainResinDiscountNum(): int
    {
        return $this->remainResinDiscountNum;
    }

    public function getResinDiscountNumLimit(): int
    {
        return $this->resinDiscountNumLimit;
    }

    public function getCurrentExpeditionNum(): int
    {
        return $this->currentExpeditionNum;
    }

    public function getMaxExpeditionNum(): int
    {
        return $this->maxExpeditionNum;
    }

    /**
     * @return Expedition[]
     */
    public function getExpeditions(): array
    {
        return $this->expeditions;
    }

    public function getCurrentHomeCoin(): int
    {
        return $this->currentHomeCoin;
    }

    public function getMaxHomeCoin(): int
    {
        return $this->maxHomeCoin;
    }

    public function getHomeCoinRecoveryTime(): string
    {
        return $this->homeCoinRecoveryTime;
    }
}
