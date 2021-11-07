<?php

declare(strict_types=1);

class Expedition
{
    protected string $avatarSideIcon;
    protected string $status;
    protected string $remainedTime;

    public function __construct(array $data)
    {
        $this->avatarSideIcon = $data['avatar_side_icon'];
        $this->status = $data['status'];
        $this->remainedTime = $data['remained_time'];
    }

    public function getAvatarSideIcon(): string
    {
        return $this->avatarSideIcon;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRemainedTime(): string
    {
        return $this->remainedTime;
    }
}
