@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="userId" class="col-md-12 col-form-label text-md-right">{!!trans('api.username')!!}</label>

                            <div class="col-md-12">
                                <input id="userId" type="text" class="form-control{{ $errors->has('fail') ? ' is-invalid' : '' }}" name="userId" value="{{ old('userId') }}" required>
                            </div>

                        </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-12 col-form-label text-md-right">{!!trans('api.email')!!}</label>

                                <div class="col-md-12">
                                    <input id="email" type="email" class="form-control{{ $errors->has('fail') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('fail'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fail') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>

                        @if (\Session::has('fail'))
                            <div class="alert alert-danger">
                                    {!! \Session::get('fail') !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" onclick="setValues()" >
                                    @lang('passwords.btn_reset_password_submit')
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0 mt-2">
                            <div class="col-md-12">
                                <a href="../login">@lang('passwords.back_login')</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

