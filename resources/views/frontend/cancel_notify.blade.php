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
            @if(isset($expiredBooking))
                <div class="p-3" style="border: 1px solid #ddd">
                    <p>予約をキャンセル時間がオーバーです。</p>
                </div>
            @else
                <div class="p-3" style="border: 1px solid #ddd">
                    <p>ご予約のキャンセルが完了いたしました<br>またのご利用をお待ちしております</p>
                </div>
            @endif

        </div>
    </div>
@endsection
