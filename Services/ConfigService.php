<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/User.php';

class ConfigService
{
    protected string $CONFIG_FILE = __DIR__ . '/../config.json';

    protected string $timezone;

    /**
     * @var User[]
     */
    protected array $users;

    public function __construct()
    {
        $file = file_get_contents($this->CONFIG_FILE);
        $json = json_decode($file, true);
        $this->timezone = $json['timezone'];
        $this->users = array_map(function ($user) {
            return new User($user);
        }, $json['users']);
    }

    public function getTimezone(): string
    {
        return $this->timezone;
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
