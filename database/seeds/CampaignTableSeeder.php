<?php

use App\Models\Campaign;
use App\Models\CampaignOption;
use App\Models\CampaignMail;
use App\Models\CampaignStore;
use Illuminate\Database\Seeder;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stringJson = '[  
                       {  
                          "id":"20190503aaaaaaaabb",
                          "name":"スタジオプラン",
                          "select":[  
                             {  
                                "name":"洋装フォトプラン",
                                "holiday_price":14000,
                                "weekday_price":12000
                             },
                             {  
                                "name":"和装・洋装「よくばりプラン」",
                                "holiday_price":14000,
                                "weekday_price":13000
                             },
                             {  
                                "name":"和装フォトプラン「寿（ことぶき）」",
                                "holiday_price":16000,
                                "weekday_price":14000
                             },
                             {  
                                "name":"和装フォトプラン「雅（みやび）」",
                                "holiday_price":18000,
                                "weekday_price":17000
                             }
                          ],
                          "type":"0",
                          "require":false,
                          "weekday_benefits":"",
                          "holiday_benefits":"",
                          "parent_id":"20190503aaaaaaaabb"
                       },
                       {  
                          "id":"20190503q99keebb",
                          "name":"ロケーション撮影",
                          "select":[  
                             {  
                                "name":"洋装フォトプラン",
                                "holiday_price":22000,
                                "weekday_price":20000
                             },
                             {  
                                "name":"和装・洋装「よくばりプラン」",
                                "holiday_price":23000,
                                "weekday_price":20000
                             },
                             {  
                                "name":"和装フォトプラン「寿（ことぶき）」",
                                "holiday_price":24000,
                                "weekday_price":20000
                             },
                             {  
                                "name":"和装フォトプラン「雅（みやび）」",
                                "holiday_price":26000,
                                "weekday_price":20000
                             }
                          ],
                          "type":"1",
                          "require":true,
                          "weekday_benefits":"",
                          "holiday_benefits":"",
                          "parent_id":"20190503q99keebb"
                       }
                    ]';

        Campaign::create([
            'name' => 'wedding campaign',
            'code' => '68127421470ahjsdwetsdidad',
            'web_name' => '結婚式キャンペーン',
            'time' => '20',
            'photo_id' => 2,
            'is_display_calendar' => 1,
            'is_enable' => rand(0,1),
            'comment' => '',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        CampaignOption::create([
            'content' => $stringJson,
            'type' => 1 ,
            'start_time' => '2019-06-01 16:32:00',
            'end_time' => '2019-07-01 16:32:00',
            'weekday_fee' => '12000',
            'holiday_fee' => '14000',
            'weekday_benefits' => 'wedding campaign',
            'holiday_benefits' => 'wedding campaign',
            'campaign_id' => 1,
            'is_deleted' => false
        ]);

        CampaignStore::create([
            'campaign_id' => 1,
            'store_id' => 2,
            'is_deleted' => false
        ]);

        CampaignStore::create([
            'campaign_id' => 1,
            'store_id' => 1,
            'is_deleted' => false
        ]);

        CampaignMail::create([
            'campaign_id' => 1,
            'is_deleted' => false,
            'type' => '1',
            'day' => '1',
            'template' => ''
        ]);


        $stringJson = '[  
                           {  
                              "id":"1559211495.7457i2fD5h1WnUGAoIzm738",
                              "name":"スタジオプラン",
                              "select":[  
                                 {  
                                    "name":"洋装フォトプラン",
                                    "holiday_price":28000,
                                    "weekday_price":25000
                                 },
                                 {  
                                    "name":"和装・洋装「よくばりプラン」",
                                    "holiday_price":28000,
                                    "weekday_price":25000
                                 },
                                 {  
                                    "name":"和装フォトプラン「寿（ことぶき）」",
                                    "holiday_price":28000,
                                    "weekday_price":25000
                                 },
                                 {  
                                    "name":"和装フォトプラン「雅（みやび）」",
                                    "holiday_price":28000,
                                    "weekday_price":25000
                                 }
                              ],
                              "type":"0",
                              "require":false,
                              "weekday_benefits":"",
                              "holiday_benefits":"",
                              "parent_id":"20190503aaaaaaaabb"
                           },
                           {  
                              "id":"1559211495.745NQQLYny4vEgwxe7rMPgp",
                              "name":"ロケーション撮影",
                              "select":[  
                                 {  
                                    "name":"洋装フォトプラン",
                                    "holiday_price":46000,
                                    "weekday_price":40000
                                 },
                                 {  
                                    "name":"和装・洋装「よくばりプラン」",
                                    "holiday_price":46000,
                                    "weekday_price":40000
                                 },
                                 {  
                                    "name":"和装フォトプラン「寿（ことぶき）」",
                                    "holiday_price":46000,
                                    "weekday_price":40000
                                 },
                                 {  
                                    "name":"和装フォトプラン「雅（みやび）」",
                                    "holiday_price":46000,
                                    "weekday_price":40000
                                 }
                              ],
                              "type":"1",
                              "require":true,
                              "weekday_benefits":"",
                              "holiday_benefits":"",
                              "parent_id":"20190503q99keebb"
                           }
                        ]';

        Campaign::create([
            'name' => 'even campaign',
            'code' => '1559211391.759H7Zmh8svrrYRbMcT6O9C',
            'web_name' => 'イベントキャンペーン',
            'time' => '30',
            'photo_id' => 2,
            'is_display_calendar' => 1,
            'is_enable' => rand(0,1),
            'comment' => '',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        CampaignOption::create([
            'content' => $stringJson,
            'type' => 1 ,
            'start_time' => '2019-06-15 17:17:00',
            'end_time' => '2019-07-31 17:17:00',
            'weekday_fee' => '25000',
            'holiday_fee' => '30000',
            'weekday_benefits' => 'event campaign',
            'holiday_benefits' => 'event campaign',
            'campaign_id' => 2,
            'is_deleted' => false
        ]);

        CampaignStore::create([
            'campaign_id' => 2,
            'store_id' => 3,
            'is_deleted' => false
        ]);


        $stringJson = '[  
                           {  
                              "id":"1559211846.748pPLOz5u8bDpKaM5x8TE7",
                              "name":"誕生日撮影プラン",
                              "select":[  
                                 {  
                                    "name":"お子様の衣装レンタルが無料",
                                    "holiday_price":45000,
                                    "weekday_price":35000
                                 },
                                 {  
                                    "name":"家族みんなで記念撮影",
                                    "holiday_price":55000,
                                    "weekday_price":40000
                                 },
                                 {  
                                    "name":"オリジナルのケーキやネームボードと撮影",
                                    "holiday_price":50000,
                                    "weekday_price":42000
                                 },
                                 {  
                                    "name":"一組ずつ貸切の個室スタジオ",
                                    "holiday_price":40000,
                                    "weekday_price":35000
                                 }
                              ],
                              "type":"0",
                              "require":false,
                              "weekday_benefits":"",
                              "holiday_benefits":"",
                              "parent_id":"20190503aaaaaaaacc"
                           },
                           {  
                              "id":"1559211846.7492EjZCO0sDyBn4m8RUgCB",
                              "name":"卒業撮影プラン",
                              "select":[  
                                 {  
                                    "name":"何ポーズでも無料で撮影！",
                                    "holiday_price":64000,
                                    "weekday_price":60000
                                 },
                                 {  
                                    "name":"ご予約をムリに詰め込みません！",
                                    "holiday_price":64000,
                                    "weekday_price":60000
                                 },
                                 {  
                                    "name":"画像データをご用意！",
                                    "holiday_price":64000,
                                    "weekday_price":60000
                                 },
                                 {  
                                    "name":"カメラのキタムラの高品質プリント",
                                    "holiday_price":64000,
                                    "weekday_price":60000
                                 },
                                 {  
                                    "name":"予約変更や撮り直しも無料で対応！",
                                    "holiday_price":64000,
                                    "weekday_price":60000
                                 }
                              ],
                              "type":"1",
                              "require":true,
                              "weekday_benefits":"",
                              "holiday_benefits":"",
                              "parent_id":"20190503q99keebbcc"
                           }
                        ]';

        Campaign::create([
            'name' => 'trip campaign',
            'code' => '1559211796.92RW6dNuVXfDFElDbaIdnd',
            'web_name' => '旅行キャンペーン',
            'time' => '45',
            'photo_id' => 3,
            'is_display_calendar' => 1,
            'is_enable' => rand(0,1),
            'comment' => '',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        CampaignOption::create([
            'content' => $stringJson,
            'type' => 1 ,
            'start_time' => '2019-06-13 17:23:00',
            'end_time' => '2019-06-29 17:24:00',
            'weekday_fee' => '25000',
            'holiday_fee' => '30000',
            'weekday_benefits' => 'trip campaign',
            'holiday_benefits' => 'trip campaign',
            'campaign_id' => 3,
            'is_deleted' => false
        ]);

        CampaignStore::create([
            'campaign_id' => 3,
            'store_id' => 2,
            'is_deleted' => false
        ]);
    }
}
