<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => '一致しないレコードがあります。',
    'throttle' => 'ログインリクエストが多すぎ. :seconds秒後、もう一度 ログインしてください。',
    'user_lock' => 'アカウントがロックされています。',
    'time_to_lock' => 'ログインは:number/:total回失敗します。 もう一度やり直してください。',
    'unlock' => [
        'wrong' => 'あなたのユーザー名またはメールは間違っています。',
        'sendmail' => '管理者へロック解除リクエストのメールを送信しました。',
    ],
    'access' => 'すみません。このリンク（:ip）にアクセスできません。'
];
