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
                <form method="POST" action="{{ route('plan.confirm', Request::segment(3) ) }}" id="form">
                    @csrf
                    <h3>ご予約のキャンセル・変更</h3>
                    <p>ご予約時に登録いただきましたお客様の電話番号をご入力ください</p>
                    <input class="form-control" name="phone" type="text"/>
                    @if($errors->any())
                        @if ($errors->first('exist'))
                            <p class="alert alert-danger mt-2">{{ ($errors->first('exist')) }}</p>
                        @endif
                    @endif
                    <button class="btn btn-primary mt-3" type="submit">確認</button>
                </form>
            </div>
        </div>
    </div>
@endsection
