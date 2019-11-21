<style>
    html, body {
        height: 100%;
        background-color: #eee;
    }

    .container > div {
        border-radius: 6px;
        margin: 20px;
        background-color: #fff;
    }
</style>
@extends('layouts.font-end')
@section('content')
    <div id="app">
        <div class="container p-5">
            <div class="p-3" style="border: 1px solid #ddd">
                <p>お客様のご予約が完了いたしました</p>
                <p>※必ずご確認くださいませ※</p>
                <br>
                <p>ご予約確認のメールをご入力いただきましたメールアドレス宛にお送りしております。</p>
                <p>確認メールが届かない場合は、以下の状況が考えられます。必ずご確認くださいませ。</p>
                <br>
                <p>〇迷惑メールボックスに振り分けられている</p>
                <p>〇ドメイン指定受信などをされている<（<strong>info@photomaga.com</strong>からのメールを受け取れるように設定をお願いいたします）</p>
                <p>〇ご予約がきちんと完了していない</p>
                <br>
                <p>ご予約に関する、ご不明な点・ご質問等ございましたら、サポートセンターへお問い合わせくださいませ。</p>
                <br>
                <p><strong>サポートセンター</strong></p>
                <p><strong>営業時間　平日・土日祝日：10:00~17:00</strong></p>
                <p><strong>078-925-3192</strong></p>
            </div>
        </div>
    </div>
@endsection
