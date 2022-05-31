<?php
return [
    'in_production' => false,
    'name' => 'odung.com',
    'upload' => [
        'folder' => 'uploads/filemanager',
        'maxFiles' => 100,
        'maxSize' => 26843545600,
        'extensions' => 'png, jpg, gif, mp4, avi, mov, wmv',
    ],
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'odung@gmail.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'jwt-config' => [
        'iss' => 'http://lucnn.vnn',
        'aud' => 'http://lucnn.vnc',
        'time' => time() + 3600 * 24 * 30,
        'time_refresh' => time() + 3600 * 24 * 90,
        'key' => 'Lucnn',
        'alt' => 'HS384',
    ],
    'OneSignal' => [
        'URL_API' => 'https://onesignal.com/api/v1/notifications',
        'CUS' => [
            'APP_ID' => '03623315-dfcb-48f2-8d1c-c0d024be1206',
            'API_KEY' => 'YWJiM2E5MmMtZDkwYy00OGMzLWFmZjItN2EwYmY5OGQ4YWUz',
        ],
        'SHIP' => [
            'APP_ID' => 'eb0ea1a6-cd00-4fcb-b75b-28b34ac3ed7d',
            'API_KEY' => 'OGNhMjQyMGItYjUyMS00MmRmLTg4ZjItMTQ3ZjMxMmFlM2Zm',
        ],
    ],
    'esms' => [
        'base_url' => 'http://rest.esms.vn/MainService.svc/json/',
        'api_key' => '7D5EFAC09E659C719EC87A745',
        'api_secret' => '64D3033091E175DE3C664C',
    ],
    'backendAuthKey' => 'p2@Vx6pn.CTB!dxqRBMA',
];
