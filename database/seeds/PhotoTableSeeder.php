<?php

use App\Models\Photo;
use App\Models\PhotoOption;
use Illuminate\Database\Seeder;

class PhotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stringJson = '[{
            "id":"20190503aaaaaaaa",
            "name":"パパママお着物お支度プラン",
            "select":[
                {
                    "name":"ママのみ",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"パパのみ",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"パパママ両方",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"不要",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"0",
            "require":false,
            "weekday_benefits":"",
            "holiday_benefits":""
        },  {
            "id":"20190503q99kee",
            "name":"キッズプラン",
            "select":[
                {
                    "name":"両親",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"1",
            "require":true,
            "weekday_benefits":"",
            "holiday_benefits":""
            }
        ]';

        Photo::create([
            'name' => '一○一撮影',
            'is_enable' => rand(0,1),
            'cash_id' =>rand(1,2),
            'comment' => '家族撮影',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        PhotoOption::create([
            'content' => $stringJson,
            'photo_id' => 1 ,
            'is_deleted' => false
        ]);


        $stringJson = '[{
            "id":"20190503aaaaaaaabb",
            "name":"スタジオプラン",
            "select":[
                {
                    "name":"洋装フォトプラン",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装・洋装「よくばりプラン」",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装フォトプラン「寿（ことぶき）」",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装フォトプラン「雅（みやび）」",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"0",
            "require":false,
            "weekday_benefits":"",
            "holiday_benefits":""
        },  {
            "id":"20190503q99keebb",
            "name":"ロケーション撮影",
            "select":[
                {
                    "name":"洋装フォトプラン",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装・洋装「よくばりプラン」",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装フォトプラン「寿（ことぶき）」",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"和装フォトプラン「雅（みやび）」",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"1",
            "require":true,
            "weekday_benefits":"",
            "holiday_benefits":""
            }
        ]';

        Photo::create([
            'name' => '一二三撮影',
            'is_enable' => rand(0,1),
            'cash_id' =>rand(1,2),
            'comment' => '結婚式撮影',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        PhotoOption::create([
            'content' => $stringJson,
            'photo_id' => 2 ,
            'is_deleted' => false
        ]);


        $stringJson = '[{
            "id":"20190503aaaaaaaacc",
            "name":"誕生日撮影プラン",
            "select":[
                {
                    "name":"お子様の衣装レンタルが無料",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"家族みんなで記念撮影",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"オリジナルのケーキやネームボードと撮影",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"一組ずつ貸切の個室スタジオ",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"0",
            "require":false,
            "weekday_benefits":"",
            "holiday_benefits":""
        },  {
            "id":"20190503q99keebbcc",
            "name":"卒業撮影プラン",
            "select":[
                {
                    "name":"何ポーズでも無料で撮影！",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"ご予約をムリに詰め込みません！",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"画像データをご用意！",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"カメラのキタムラの高品質プリント",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"予約変更や撮り直しも無料で対応！",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"1",
            "require":true,
            "weekday_benefits":"",
            "holiday_benefits":""
            }
        ]';

        Photo::create([
            'name' => '四五五撮影',
            'is_enable' => rand(0,1),
            'cash_id' =>rand(1,2),
            'comment' => 'イベント撮影',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        PhotoOption::create([
            'content' => $stringJson,
            'photo_id' => 3,
            'is_deleted' => false
        ]);

        $stringJson = '[{
            "id":"20190503aaaaaaaadd",
            "name":"東京プラン",
            "select":[
                {
                    "name":"上野動物園",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"東京スカ一ツリー",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"品川",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"雷門",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"0",
            "require":false,
            "weekday_benefits":"",
            "holiday_benefits":""
        },  {
            "id":"20190503q99keebbdd",
            "name":"大阪プラン",
            "select":[
                {
                    "name":"大坂城",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"ユニバーサル",
                    "holiday_price":"",
                    "weekday_price":""
                },{
                    "name":"なんばグランド花月",
                    "holiday_price":"",
                    "weekday_price":""
                }
            ],
            "type":"1",
            "require":true,
            "weekday_benefits":"",
            "holiday_benefits":""
            }
        ]';

        Photo::create([
            'name' => '七五二撮影',
            'is_enable' => rand(0,1),
            'cash_id' =>rand(1,2),
            'comment' => '旅行撮影',
            'is_deleted' => false,
            'last_update_by' => 1
        ]);

        PhotoOption::create([
            'content' => $stringJson,
            'photo_id' => 4,
            'is_deleted' => false
        ]);
    }
}
