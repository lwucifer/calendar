@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(Session::has('success'))
                <div class="col-md-5">
                    <div class="alert alert-success">
                        <strong>@lang('passwords.send_request_unlock')</strong>
                    </div>
                    <a class="btn btn-link pl-0" href="{{ route('login') }}">
                        @lang('passwords.back_login')
                    </a>
                </div>

            @else
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('post.account.unlock') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="username"
                                           class="col-md-12 col-form-label text-md-right">{!!trans('api.username')!!}</label>

                                    <div class="col-md-12">
                                        <input id="username" type="text"
                                               class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                               name="username" value="{{ old('username') }}" required autofocus>

                                        @if ($errors->has('username'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email"
                                           class="col-md-12 col-form-label text-md-right">{!!trans('api.email')!!}</label>

                                    <div class="col-md-12">
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               value="{{ old('email') }}" name="email" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        @if ($errors->has('status'))
                                            <div class="alert alert-warning">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            @lang('passwords.unlock_submit')
                                        </button>
                                        <br>
                                        <a class="btn btn-link pl-0" href="{{ route('login') }}">
                                            @lang('passwords.back_login')
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
