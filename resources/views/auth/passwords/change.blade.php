@extends('layouts.app')

@section('content')
    <style>
        .help-block strong{
            color:red;
        }
    </style>
    <div id="app">
        <main class="py-4">
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                        <a href="/">Return to homepage</a>
                                    @else
                                        @if(isset($expired))
                                            <div class="alert alert-info">
                                                @lang('passwords.password_expired_msg_1')<br>
                                                　 @lang('passwords.password_expired_msg_2')
                                            </div>
                                        @endif
                                        <form class="form-horizontal" method="POST"
                                              action="{{ route('password.post_change') }}">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                                <label for="current_password"
                                                       class="col-md-4 control-label">@lang('passwords.current_password')</label>

                                                <div class="col-md-6">
                                                    <input id="current_password" type="password" class="form-control"
                                                           name="current_password" required="" placeholder="@lang('passwords.password_placeholder')">

                                                    @if ($errors->has('current_password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('current_password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label for="password" class="col-md-4 control-label">@lang('passwords.new_password')</label>

                                                <div class="col-md-6">
                                                    <input id="password" type="password" class="form-control"
                                                           name="password" required="" placeholder="@lang('passwords.password_placeholder')">

                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                                <label for="password-confirm"
                                                       class="col-md-4 control-label">@lang('passwords.new_password_confirm')</label>
                                                <div class="col-md-6">
                                                    <input id="password-confirm" type="password" class="form-control"
                                                           name="password_confirmation" required="" placeholder="@lang('passwords.password_placeholder')">

                                                    @if ($errors->has('password_confirmation'))
                                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        @lang('passwords.change_password_submit')
                                                    </button>
                                                </div>
                                            </div>
                                            {{--<div class="form-group">--}}
                                                {{--<div class="col-md-6 col-md-offset-4">--}}
                                                    {{--@if(isset($expired))--}}
                                                        {{--<a href="{{ route('logout') }}" onclick="event.preventDefault();--}}
                                                                {{--document.getElementById('logout-form').submit();">元の画面へ</a>--}}
                                                    {{--@else--}}
                                                        {{--<a href="/">元の画面へ</a>--}}
                                                    {{--@endif--}}

                                                {{--</div>--}}
                                            {{--</div>--}}
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
