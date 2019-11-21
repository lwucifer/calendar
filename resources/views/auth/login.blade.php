@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label text-md-right">{!!trans('api.email')!!}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label text-md-right">{!!trans('api.password')!!}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label pt-0" for="remember">
                                        {{ __('ログイン状態を保存する') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                @if ($errors->has('timeToLock'))
                                    <div class="alert alert-warning">
                                        <strong>{{ $errors->first('timeToLock') }} </strong>
                                    </div>
                                @endif
                                @if ($errors->has('locked'))
                                <div class="alert alert-warning">
                                    <strong>{{ $errors->first('locked') }}</strong>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    @lang('api.submit')
                                </button>
                                <br>
                                @if($errors->has('locked'))
                                    <a class="btn btn-link pl-0" href="{{ route('account.unlock') }}">
                                        @lang('api.unlock_screen')
                                    </a>
                                @else
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link pl-0" href="{{ route('password.request') }}">
                                            @lang('api.password_reset')
                                        </a>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
