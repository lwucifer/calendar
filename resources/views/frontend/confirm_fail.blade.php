<style>
    html, body {
        height: 100%;
        background-color: #eee;
    }

    .container > div {
        border-radius: 6px;
        background-color: #fff;
        margin: 20px;
    }
</style>
@extends('layouts.font-end')
@section('content')
    <div id="app">
        <div class="container p-5">
            <div class="p-3" style="border: 1px solid #ddd">
                <p>カレンダーは既に購入されています。別のカレンダーを選択してください。</p>
                <a href="{{ url('/campaign') }}" class="btn btn-primary">キャンペーン一URL覧</a>
            </div>
        </div>
    </div>
@endsection
