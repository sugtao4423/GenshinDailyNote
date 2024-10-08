<?php

declare(strict_types=1);

class HoYoLabRepository
{
    private string $DAILY_NOTE_API_URL = 'https://bbs-api-os.hoyolab.com/game_record/app/genshin/api/dailyNote';
    private string $APP_VERSION = '3.0.1';

    private string $uid;
    private string $cookie;

    public function __construct(string $uid, string $cookie)
    {
        $this->uid = $uid;
        $this->cookie = $cookie;
    }

    public function getDailyNoteData(): ?string
    {
        $query = [
            'server' => 'os_asia',
            'role_id' => $this->uid,
        ];
        $url = $this->DAILY_NOTE_API_URL . '?' . http_build_query($query);

        $header = [
            'Accept-Language: ja',
            'Accept: application/json, text/plain, */*',
            'Cookie: ' . $this->cookie,
            'Origin: https://act.hoyolab.com',
            'Referer: https://act.hoyolab.com/',
            'x-rpc-app_version: ' . $this->APP_VERSION,
            'x-rpc-client_type: 5',
            'x-rpc-language: ja-jp',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === false) {
            return null;
        }
        return $output;
    }
}
