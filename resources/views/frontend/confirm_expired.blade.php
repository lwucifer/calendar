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
                <p>予約は期限切れです。別のカレンダーを選択してください。</p>
                <a href="{{ url('/campaign') }}" class="btn btn-primary">キャンペーン一URL覧</a>
            </div>
        </div>
    </div>
@endsection
