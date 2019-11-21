@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 card">
                <div class="card-body">
                    <strong>@lang('auth.access', ['ip' => $ip])</strong>
                    <a class="btn btn-link pl-0" href="{{ route('login') }}">
                        @lang('passwords.back_login')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
