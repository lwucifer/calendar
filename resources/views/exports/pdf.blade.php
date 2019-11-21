<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>七五三チャート2016</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .border-dot-2 {
            border-bottom: 2px dotted #222;
            min-width: 50px;
        }

        .color-red {
            color: black;
        }

        .table-bordered, .border {
            border-width: 2px !important;
            border-color: #333 !important;
        }

        .table-bordered td, .table-bordered th {
            border-color: #333 !important;
        }

        .table td, .table th {
            padding: 0 0.25rem
        }

        html, body {
            font-family: ipag;
            font-size: 12px;
        }

        p {
            margin-bottom: 0
        }

        .table-s {
            width: 100%;
        }

        .table-s tbody td {
            border: 2px solid #333;
        }

        .name_size {
            font-size: 27px;
        }

        .phone_zize {
            font-size: 25px;
        }

        .zipcode_size, .address_size {
            font-size: 20px;
        }
        .table-s {
            vertical-align: top;
        }
        th.border {
            border: 2px solid #333;
            border-bottom: 0;
            padding: 0 1rem;
        }
        .table-s td {
            vertical-align: middle;
        }
        p {
            margin-bottom: 0;
        }
        .table-list td, th {
            padding: 0 5px !important;
        }
        p.mt-1 {
            margin-top: 0 !important;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-2">
    <strong style="font-size: 11px; font-weight: 600">
        お客様の個人情報は、当社からお客様へのご連絡、お問い合わせ、お子様の記念日や当店及び姉妹店（アンジュエール）からの撮影キャ
        ンペーン等のご案内に のみ利用させて頂きます。第3者への提供は致しません。
    </strong>
    <div style="padding: 0 1px">
        <table class="table-s">
            <thead>
            <tr>
                <th>
                    <div style="font-size: 1.75rem; font-weight: 600">
                        @if(isset($campaign_name))
                            {{ $campaign_name }}
                        @endif
                        <span class="float-right pr-3">お客様チャート</span>
                        <div style="clear: both"></div>
                    </div>
                </th>
                <th class="border">
                    <p style="font-size: 1.25rem; font-weight: 600">同意</p>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="border-bottom-0 text-center">
                    <div>
                        <span class="color-red">ふりがな </span>
                        <span class="mt-3">
                            @if(isset($mother_lastname_kana))
                                {{ $mother_lastname_kana }}
                            @endif
                            @if(isset($mother_firstname_kana))
                                {{ $mother_firstname_kana }}
                            @endif
                        </span>
                    </div>
                </td>
                <td class="text-center border-bottom-0">
                </td>
            </tr>
            <tr>
                <td class="border-top-0">
                    <div class="d-inline-block color-red pt-4">
                        <span>★お客様名<br/>（お母様）</span>
                    </div>
                    <div class="float-right">
                        <span class="pl-4 pr-4 name_size">
                            @if(isset($mother_lastname))
                                {{ $mother_lastname }}
                            @endif
                            @if(isset($mother_firstname))
                                {{ $mother_firstname }}
                            @endif
                        </span>
                        <span class="pr-3 color-red name_size">様</span>
                    </div>
                    <div style="clear: both"></div>
                </td>
                <td class="border-top-0 pl-3" style="vertical-align: text-top">
                    <div style="font-size: 1.5rem">
                        <span class="color-red">ID: </span>
                        <span>
                            @if(isset($fid))
                                {{ $fid }}
                            @endif
                        </span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="border pl-1 pt-2 border-top-0">
        <span class="color-red align-middle">★ＴＥＬ（市外局番）</span> 　
        <span class="phone_zize align-middle">
            @if(isset($new_tel))
                {{ $new_tel }}
            @endif
        </span>
    </div>
    <div class="border p-1 border-top-0 align-middle">
        <div class="d-inline-block">
            <span class="color-red">★ご住所</span>
            <span class="zipcode_size">
                @if(isset($zipcode))
                    {{ $zipcode }}
                @endif
            </span>
        </div>
        <div class="float-right color-red">（マンション・アパート名までご記入下さい）</div>
        <div class="address_size text-center">
            @if(isset($address1))
                {{ $address1 }}
            @endif
            @if(isset($address2))
                {{ $address2 }}
            @endif
            @if(isset($address3))
                {{ $address3 }}
            @endif
        </div>
        <div style="clear: both"></div>
    </div>
    <table class="table table-list table-bordered mt-1 mb-1">
        <thead>
        <tr class="text-center">
            <td style="white-space: nowrap">お子様のお名前/ふりがな</td>
            <td>年齢･性別</td>
            <td style="white-space: nowrap">生　年　月　日　</td>
            <td><b>一人写し</b></td>
            <td>お着替え</td>
            <td style="width: 35%">衣装NO./色</td>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p class="text-center">
                    <span>
                        @if(isset($children[0]['firstname_kana']))
                            {{ $children[0]['firstname_kana'] }}
                        @endif
                    </span>
                    </p>
                    <p class="mt-1"></p>
                    <p>
                        <span class="color-red">★</span>
                        <span>
                        @if(isset($children[0]['firstname']))
                                {{ $children[0]['firstname'] }}
                            @endif
                    </span>
                        <span class="color-red" style="text-align: right;">ちゃん</span>
                    </p>
                </td>
                <td class="text-center">
                    <p>
                   <span class="color-red">
                       @if(isset($children[0]['yearold']))
                           {{ $children[0]['yearold'] }}
                       @endif
                   </span>
                        <span class="color-red">才</span>
                    </p>
                    <p class="mt-1">-------</p>
                    @if(isset($children[0]['gender']))
                        @if(($children[0]['gender'] == "man"))
                            <p class="color-red">男</p>
                        @else
                            <p class="color-red">女</p>
                        @endif
                    @endif
                </td>
                <td>
                    <p class="text-center"><span></span></p>
                    <p class="mt-1"></p>
                    <span class="color-red">
                    @if(isset($children[0]['birthday']))
                            {{ $children[0]['birthday'] }}
                        @endif
                </span>
                </td>
                <td class="text-center">
                    <b>有</b>
                    <p class="mt-1">-------</p>
                    <b>無</b>
                </td>
                <td class="text-center">
                    <span>有</span>
                    <p class="mt-1">-------</p>
                    <span>無</span>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <p class="text-center">
                    <span>
                        @if(isset($children[1]['firstname_kana']))
                            {{ $children[1]['firstname_kana'] }}
                        @endif
                    </span>
                    </p>
                    <p class="mt-1"></p>
                    <p>
                        <span class="color-red">★</span>
                        <span>
                        @if(isset($children[1]['firstname']))
                                {{ $children[1]['firstname'] }}
                            @endif
                    </span>
                        <span class="color-red" style="text-align: right;">ちゃん</span>
                    </p>
                </td>
                <td class="text-center">
                    <p>
                   <span class="color-red">
                       @if(isset($children[1]['yearold']))
                           {{ $children[1]['yearold'] }}
                       @endif
                   </span>
                        <span class="color-red">才</span>
                    </p>
                    <p class="mt-1">-------</p>
                    @if(isset($children[1]['gender']))
                        @if(($children[1]['gender'] == "man"))
                            <p class="color-red">男</p>
                        @else
                            <p class="color-red">女</p>
                        @endif
                    @endif
                </td>
                <td>
                    <p class="text-center"><span></span></p>
                    <p class="mt-1"></p>
                    <span class="color-red">
                    @if(isset($children[1]['birthday']))
                            {{ $children[1]['birthday'] }}
                        @endif
                </span>
                </td>
                <td class="text-center">
                    <b>有</b>
                    <p class="mt-1">-------</p>
                    <b>無</b>
                </td>
                <td class="text-center">
                    <span>有</span>
                    <p class="mt-1">-------</p>
                    <span>無</span>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <p class="text-center">
                    <span>
                        @if(isset($children[2]['firstname_kana']))
                            {{ $children[2]['firstname_kana'] }}
                        @endif
                    </span>
                    </p>
                    <p class="mt-1"></p>
                    <p>
                        <span class="color-red">★</span>
                        <span>
                        @if(isset($children[2]['firstname']))
                                {{ $children[2]['firstname'] }}
                            @endif
                    </span>
                        <span class="color-red" style="text-align: right;">ちゃん</span>
                    </p>
                </td>
                <td class="text-center">
                    <p>
                   <span class="color-red">
                       @if(isset($children[2]['yearold']))
                           {{ $children[2]['yearold'] }}
                       @endif
                   </span>
                        <span class="color-red">才</span>
                    </p>
                    <p class="mt-1">-------</p>
                    @if(isset($children[2]['gender']))
                        @if(($children[2]['gender'] == "man"))
                            <p class="color-red">男</p>
                        @else
                            <p class="color-red">女</p>
                        @endif
                    @endif
                </td>
                <td>
                    <p class="text-center"><span></span></p>
                    <p class="mt-1"></p>
                    <span class="color-red">
                    @if(isset($children[2]['birthday']))
                            {{ $children[2]['birthday'] }}
                        @endif
                </span>
                </td>
                <td class="text-center">
                    <b>有</b>
                    <p class="mt-1">-------</p>
                    <b>無</b>
                </td>
                <td class="text-center">
                    <span>有</span>
                    <p class="mt-1">-------</p>
                    <span>無</span>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="border p-1 mt-1">
        <p><b>★《今回の撮影は何を見てお決めになられましたか？》※必須※</b></p>
        <p><b>□ YS/AG合同チラシ　　□DM　　□HP　　□友人紹介　　□スタッフ紹介</b></p>
        <p><b>□ もともと知っていたので広告見てません　　□その他（<span></span>）</b></p>
    </div>
    <div class="border p-1 border-top-0">
        <table class="table m-0">
            <tbody>
            <tr>
                <td style="width: 100px">衣装合わせ日</td>
                @if(isset($plan_date))
                    <td style="font-size: 14px">{{ $plan_date }}</td>
                @endif
                @if(isset($plan_time))
                    <td style="font-size: 14px">{{ $plan_time }}</td>
                @endif
                <td style="font-size: 14px">ご来店</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="border p-1 border-top-0">
        <table class="table m-0">
            <tbody>
            <tr>
                <td style="width: 100px">撮　影　日</td>
                @if(isset($plan_date))
                    <td style="font-size: 14px">{{ $plan_date }}</td>
                @endif
                @if(isset($plan_time))
                    <td style="font-size: 14px">{{ $plan_time }}</td>
                @endif
                <td style="font-size: 14px">ご来店</td>
            </tr>
            </tbody>
        </table>
        <p class="border-dot-2" style="padding-bottom: 5px; margin-top: 2px"></p>
        <p>
            <span style="display: inline-block; width: 100px">ご家族撮影</span>
            <span style="display: inline-block">（ 有 ・ 無 ）</span>
            <span style="margin-left: 35px; display: inline-block">ご兄弟撮影 （ 有 ・ 無 ）</span>
            　　 <span style="margin-left: 35px; display: inline-block">撮影参加人数 （　<span></span>名 ）</span>
        </p>
    </div>
    <div class="border p-1 border-top-0">
        <table class="table m-0">
            <tbody>
            <tr>
                <td style="width: 100px">お出かけ日</td>
                @if(isset($plan_date))
                    <td style="font-size: 14px">{{ $plan_date }}</td>
                @endif
                @if(isset($plan_time))
                    <td style="font-size: 14px">{{ $plan_time }}</td>
                @endif
                <td style="font-size: 14px">ご来店</td>
            </tr>
            </tbody>
        </table>
        <table class="table m-0">
            <tbody>
            <tr>
                <td style="width: 100px">セレクト</td>
                <td>当日・後日</td>
                @if(isset($plan_date))
                    <td style="font-size: 14px">{{ $plan_date }}</td>
                @endif
                @if(isset($plan_time))
                    <td style="font-size: 14px">{{ $plan_time }}</td>
                @endif
                <td style="font-size: 14px">ご来店</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="border p-1 border-top-0 pb-2">
        <p class="mb-4">メモ欄</p>
        @if(isset($options))
            @foreach($options as $op)
                <p style="white-space: normal">
                    ●
                    @if(isset($op['name']))
                        {{ $op['name'] }}
                    @endif
                </p>
                @if(isset($op['select']))
                    @foreach($op['select'] as $select)
                        <p style="white-space: normal">
                            @if($select['status'] == 0)
                                &#9633;
                            @else
                                &#9632;
                            @endif
                            @if(isset($select['name']))
                                {{ $select['name'] }}
                            @endif
                        </p>
                    @endforeach
                @endif
                <br/>
            @endforeach
        @endif
        <div style="clear: both"></div>
        <p>
            ママお出かけ （有 ・ 無） ママ持ち込み（有 ・ 無）<br/>
            祖母 （有 ・ 無） 祖父 （有 ・ 無）<br/>
            祖母お出かけ（有 ・ 無） 祖母持ち込み（有 ・ 無）<br/>
        </p>
        <div class="mt-5">
            <span class="d-inline-block border-bottom float-right"> セレクト　　年　　月　　日／担当</span>
            <span class="d-inline-block mr-5 border-bottom float-right">予約日　　年　　月　　日／担当</span>
            <div style="clear: both"></div>
        </div>
    </div>
</div>
</body>
</html>
