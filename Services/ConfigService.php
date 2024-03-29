<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/User.php';
require_once __DIR__ . '/../Config.php';

class ConfigService
{
    protected string $timezone;
    protected string $slackWebhookUrl;

    /**
     * @var User[]
     */
    protected array $users;

    public function __construct()
    {
        $this->timezone = Config::$timezone;
        $this->slackWebhookUrl = Config::$slackWebhookUrl;
        $this->users = array_map(function ($user) {
            return new User($user);
        }, Config::$users);
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getSlackWebhookUrl(): string
    {
        return $this->slackWebhookUrl;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function getUserByAlias(string $alias): ?User
    {
        foreach ($this->users as $user) {
            if (strtolower($user->getAlias()) === strtolower($alias)) {
                return $user;
            }
        }
        return null;
    }

    public function getUserBySlackUserId(string $slackUserId): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getSlackUserId() === $slackUserId) {
                return $user;
            }
        }
        return null;
    }
}
