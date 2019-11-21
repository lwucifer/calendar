<?php

return [

    'user' => [
        'id' => 'ID',
        'username' => 'Username',
        'email' => 'Email',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'kana_first_name' => 'Kana first name',
        'kana_last_name' => 'Kana last name',
        'store_id' => 'Store Id',
        'role_id' => 'Role Id',
        'phone' => 'Phone',
        'zip_code' => 'Zip code',
        'comment' => 'Comment',
        'parent_user' => 'Parent user',
        'is_enable' => 'Enable'
    ],
    'store' => [
        'id' => 'ID',
        'name' => 'Name',
        'phone' => 'Phone',
        'manager_id' => 'Manager Id',
        'sign_email' => 'Sign email',
        'comment' => 'Comment',
        'weekday_start_time' => 'Weekday start time',
        'weekday_end_time' => 'Weekday end time',
        'holiday_start_time' => 'Holiday start time',
        'holiday_end_time' => 'Holiday end time',
        'day_off_monday' => 'Day off monday',
        'day_off_tuesday' => 'Day off tuesday',
        'day_off_wednesday' => 'Day off wednesday',
        'day_off_thursday' => 'Day off thursday',
        'day_off_friday' => 'Day off friday',
        'day_off_saturday' => 'Day off saturday',
        'day_off_sunday' => 'Day off sunday',
        'fixed_days_off' => 'Fixed days off',
        'fixed_days_on' => 'Fixed days on',
        'is_enable' => 'Enable'
    ],
    'plan' => [
        'id' => 'ID',
        'store_id' => 'Store Id',
        'campaign_id' => 'Campaign Id',
        'user_id' => 'User Id',
        'date' => 'Date',
        'start_time' => 'Start time',
        'end_time' => 'End time',
        'comment' => 'Comment',
        'status' => 'Status',
        'is_enable' => 'Enable'
    ],
    'photo' => [
        'id' => 'ID',
        'name' => 'Name',
        'cash_id' => 'Cash Id',
        'comment' => 'Comment',
        'is_enable' => 'Enable'
    ],
    'campaign' => [
        'id' => 'ID',
        'name' => 'Name',
        'web_name' => 'Web name',
        'time' => 'Time',
        'photo_id' => 'Photo Id',
        'is_display_calendar' => 'Display calendar',
        'comment' => 'Comment',
        'is_enable' => 'Enable'
    ],
    'file' => [
        'user' => 'user',
        'campaign' => 'campaign',
        'plan' => 'plan',
        'store' => 'store',
        'photo' => 'photo'
    ]
];
