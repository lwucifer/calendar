<?php

return [

    'user' => [
        'id' => 'ID',
        'username' => 'ユーザー名',
        'email' => 'メール',
        'first_name' => '名前',
        'last_name' => '名前',
        'kana_first_name' => '名前',
        'kana_last_name' => '名前(かな)',
        'store_id' => '所属店舗ID',
        'role_id' => 'アカウント区分ID',
        'phone' => '電話番号',
        'zip_code' => '郵便番号',
        'comment' => 'コメント',
        'parent_user' => '親アカウントID',
        'is_enable' => '使用'
    ],
    'store' => [
        'id' => 'ID',
        'name' => '店舗名',
        'phone' => '電話番号',
        'manager_id' => '店長ID',
        'sign_email' => 'メール署名',
        'comment' => 'コメント',
        'weekday_start_time' => '平日の開始時刻',
        'weekday_end_time' => '平日の終了時刻',
        'holiday_start_time' => '休日の開始時間',
        'holiday_end_time' => '休日の終了時刻',
        'day_off_monday' => '月',
        'day_off_tuesday' => '火',
        'day_off_wednesday' => '水',
        'day_off_thursday' => '木',
        'day_off_friday' => '金',
        'day_off_saturday' => '土',
        'day_off_sunday' => '日',
        'fixed_days_off' => '定休日',
        'fixed_days_on' => '固定日',
        'is_enable' => '使用'
    ],
    'plan' => [
        'id' => 'ID',
        'store_id' => '店舗ID',
        'campaign_id' => 'キャンペーンID',
        'user_id' => 'アカウントID',
        'date' => '日付',
        'start_time' => '始まる時間',
        'end_time' => '終了時間',
        'comment' => 'コメント',
        'status' => '状態',
        'is_enable' => '使用'
    ],
    'photo' => [
        'id' => 'ID',
        'name' => '名',
        'cash_id' => 'レジ部門',
        'comment' => 'コメント',
        'is_enable' => '使用'
    ],
    'campaign' => [
        'id' => 'ID',
        'name' => '名',
        'web_name' => 'ウェブ名',
        'time' => '時間',
        'photo_id' => '撮影種類ID',
        'is_display_calendar' => 'カレンダーを表示',
        'comment' => 'コメント',
        'is_enable' => '使用'
    ],
    'file' => [
        'user' => 'アカウント一覧',
        'campaign' => 'キャンペーン一覧',
        'plan' => '予約一覧',
        'store' => '店舗一覧',
        'photo' => '撮影種類一覧'
    ]
];
