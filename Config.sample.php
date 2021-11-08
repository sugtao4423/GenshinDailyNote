<?php

declare(strict_types=1);

class Config
{
    public static string $timezone = 'Asia/Tokyo';

    public static string $slackWebhookUrl = 'https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX';

    public static array $users = [
        [
            'alias' => 'User Alias Name',
            'giUid' => 'Genshin Impact uid',
            'hoyolabCookie' => 'HoYoLab Cookie',
            'slackUserId' => 'U~~~~~',
        ],
    ];
}
