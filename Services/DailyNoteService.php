<?php

declare(strict_types=1);

require_once __DIR__ . '/../Data/User.php';
require_once __DIR__ . '/../Repositories/HoYoLabRepository.php';
require_once __DIR__ . '/../Data/DailyNote.php';

class DailyNoteService
{
    public static function getDailyNote(User $user): ?DailyNote
    {
        $repo = new HoYoLabRepository($user->getGiUid(), $user->getHoyolabCookie());
        $data = $repo->getDailyNoteData();
        if ($data !== null) {
            $json = json_decode($data, true);
            if ($json['message'] === 'OK') {
                return new DailyNote($json['data']);
            }
        }
        return null;
    }
}
