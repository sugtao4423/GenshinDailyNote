<?php

declare(strict_types=1);

class Config
{
    protected string $alias;
    protected string $giUid;
    protected string $hoyolabCookie;
    protected string $slackUserId;

    public function __construct(array $data)
    {
        $this->alias = $data['alias'];
        $this->giUid = $data['giUid'];
        $this->hoyolabCookie = $data['hoyolabCookie'];
        $this->slackUserId = $data['slackUserId'];
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getGiUid(): string
    {
        return $this->giUid;
    }

    public function getHoyolabCookie(): string
    {
        return $this->hoyolabCookie;
    }

    public function getSlackUserId(): string
    {
        return $this->slackUserId;
    }
}
