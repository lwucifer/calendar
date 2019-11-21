@extends('layouts.font-end')

@section('content')
    <div id="app">
        <section class="date-time" id="step1">
            <div class="container">
                <div>
                    <div class="row">
                        <div class="col dates">
                            @if($errors->any())
                                @if ($errors->first('exist'))
                                    <h6 class="alert alert-danger">{{ ($errors->first('exist')) }}</h6>
                                @else
                                    @foreach(json_decode($errors->first()) as $values)
                                        @foreach($values[0] as $key => $error)
                                            <h6 class="alert alert-danger">{{ $key }}: {{ $error[0] }}</h6>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endif
                            <div class="row">
                                <div class="col-10">
                                    <h2>{{ $campaign_name }}</h2>
                                </div>
                            </div>
                            <div class="hidden" style="display: none;">
                                <span class="h5 mr-2">{!!trans('calendar.list_store')!!}</span>
                                <select id="switch_store" class="form-control max-w-200 d-inline-block" onchange="getCalendar(event)">
                                    <option value="{{ $store_code }}">{{ $store_name }}</option>
                                </select>
                            </div>
                            @yield('calendar')
                        </div>
                    </div>
                    <div>
                        <div class="times">
                            <h4><span id="idate"></span> {!!trans('calendar.subtitle_1_2')!!}</h4>
                            <div class="mt-2 content">
                                <div class="form-group align-normal mb-2">
                                    <label class="mr-2">{!!trans('calendar.code')!!}</label>
                                    <div class="d-inline-block w-400">
                                        <input id="code_temp" type="text" name="code" readonly class="form-control d-inline-block w-400"/>
                                        <span style="display: none" class="alert-danger alert w-400 mb-0" id="code_error">
                                            {!!trans('calendar.code_error')!!}</span>
                                    </div>
                                </div>
                                <ul id="table-times" class="row m-0"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="options" id="step2">
            <div class="container">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h3>{!!trans('calendar.title_2')!!}</h3>
                            <div class="mt-4 mb-3">
                                <div id="campaign_option" class="mt-4"></div>
                                <span style="display: none" class="alert alert-danger" id="none_opiton">{!!trans('calendar.option_none')!!}</span>
                                <span style="display: none" class="alert alert-danger" id="require_empty">{!!trans('calendar.require_empty')!!}</span>
                            </div>
                            <a class="btn btn-dark" onclick="smoothScroll($('#step1'))">{!!trans('calendar.back')!!}</a>
                            <a class="btn btn-primary" onclick="toConfirm()">{!!trans('calendar.submit')!!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="confirm" id="step3">
            <div class="container">
                <div class="box">
                    <div class="row">
                        <form class="col" method="POST" action="{{ route('plan.create') }}" id="form">
                            @csrf
                            <h3>{!!trans('calendar.title_3')!!}</h3>
                            <div class="mt-4">
                                <table class="mb-5 table-custom">
                                    <tr style="display: none">
                                        <td>{!!trans('calendar.plan_name')!!}</td>
                                        <td>
                                            <input id="plan" name="name" type="text" class="form-control w-400" value="{{ \Illuminate\Support\Str::random(20) }}"/>
                                            <span style="display: none" id="plan_error" class="alert alert-danger w-400">
                                                {!!trans('calendar.plan_name_none')!!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.campaign_name')!!}</td>
                                        <td>
                                            <input class="form-control disabled" id="campaign_name"/>
                                            <input id="campaign" value="" name="campaign_code" type="hidden"/>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.store')!!}</td>
                                        <td>
                                            <input class="form-control disabled" id="store_name"/>
                                            <input id="store" value="" name="store_code" type="hidden"/>
                                            <input id="content" type="hidden" value="" name="content"/>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.date')!!}</td>
                                        <td>
                                            <input id="date" value="" name="date" type="text" class="form-control disabled"/>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.time')!!}</td>
                                        <td>
                                            <select id="select_time" class="form-control d-inline-block" onChange="selectTime(this.value)">
                                            </select>
                                            <input id="start_time" value="" name="start_time" type="hidden" class="w-200 form-control disabled d-inline-block"/>
                                            <input id="end_time" value="" name="end_time" type="hidden" class="w-200 form-control disabled d-inline-block"/>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.option')!!}</td>
                                        <td>
                                            <span id="list_option" class="form-control w-400 h-auto"></span>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.code')!!}</td>
                                        <td class="position-relative">
                                            <input id="code" value="" name="code" type="text" class="form-control disabled"/>
                                        </td>
                                    </tr>
                                    <tr><td>{!!trans('calendar.price')!!}</td>
                                        <td class="position-relative">
                                            <span class="sub-price">円</span>
                                            <input id="price" value="" name="price" type="text" class="form-control disabled"/>
                                        </td>
                                    </tr>
                                </table>
                                <h5>{!!trans('calendar.subtitle_3_1')!!}</h5>
                                <table class="mt-4 mb-4 table-custom">
                                    <tr>
                                        <td>{!!trans('calendar.name')!!}</td>
                                        <td>
                                            <input id="user_name" class="form-control w-400" type="text" name="user_name"/>
                                            <span style="display: none" class="alert-danger alert w-200" id="name_error">
                                            {!!trans('calendar.name_none')!!}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{!!trans('calendar.phone')!!}</td>
                                        <td class="position-relative">
                                            <input id="phone" class="form-control col-sm-6 w-400" type="text" name="phone"/>
                                            <span style="display: none" id="phone_error" class="alert alert-danger w-400">
                                                {!!trans('calendar.phone_none')!!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{!!trans('calendar.email')!!}</td>
                                        <td>
                                            <input  id="email" class="form-control col-sm-6 w-400" type="email" name="email"/>
                                            <span style="display: none" id="email_error" class="alert alert-danger w-400">
                                                {!!trans('calendar.email_none')!!}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <button type="button" class="btn btn-dark btn-final" onclick="smoothScroll($('#step2'))">{!!trans('calendar.back')!!}</button>
                            <button onclick="checkSubmit()" class="btn btn-primary btn-final" type="button">{!!trans('calendar.submit')!!}</button>
                            <button style="display: none" id="submit" type="submit"></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @yield('script')
@endsection


