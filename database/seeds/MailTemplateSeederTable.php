<?php

use App\Models\MailTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Lang;


class MailTemplateSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MailTemplate::create([
            'template' => 'mail_plan_request_user',
            'title' => Lang::get('calendar.mail.subject'),
            'content' => 'プラン名:[@var]
店舗名:[@var]
日:[@var]
始まる時間:[@var]
終了時間:[@var]
確認URL:[@var]
24時間以内にメール記載のURLより確定をお願いします
24時間経過しても予約が確定いただけない場合、自動的に仮予約はキャンセルとなります。',
            'status' => MailTemplate::STATUS_ACTIVE,
        ]);

        MailTemplate::create([
            'template' => 'mail_plan_admin',
            'title' => Lang::get('calendar.mail.subject'),
            'content' => '
ご撮影プラン名:  [@var]
ご利用店舗名:   [@var]
ご撮影日:   [@var]
ご来店時間:   [@var]',
            'status' => MailTemplate::STATUS_ACTIVE,
        ]);

        MailTemplate::create([
            'template' => 'mail_cancel_plan',
            'title' => Lang::get('calendar.mail.title_cancel'),
            'content' => 'こんにちは、[＠username]様
            
以下のご予約のキャンセルを承りました。

キャンペーン名:[@var]
ご予約店舗名:[@var]
ご撮影日:[@var]
ご来店時間:[@var]
オプション内容・料金：[@var]

またのご予約をお待ちしております。
ご予約の空き状況はこちらから▼
[@var]',
            'status' => MailTemplate::STATUS_ACTIVE,
        ]);
    }
}
