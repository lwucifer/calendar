<style>
    html, body{
        height: 100%;
        background-color: #eee;
    }
    .container >div{
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
                <h3 class="mb-3">予約内容</h3>
                <table class="table table-bordered">
                    <tr>
                        <td>キャンペーン名</td>
                        <td>{{ $plan['campaign']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>店舗名</td>
                        <td>{{ $plan['store']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>日</td>
                        <td>{{ $plan['date'] }}</td>
                    </tr>
                    <tr>
                        <td>時間</td>
                        <td>{{ $plan['start_time'] }}~{{ $plan['end_time'] }}</td>
                    </tr>
                    <tr>
                        <td>お名前</td>
                        <td>{{ $plan['user_name'] }}</td>
                    </tr>
                    <tr>
                        <td>電話番号</td>
                        <td>{{ $plan['user_phone'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
