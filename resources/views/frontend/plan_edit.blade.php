@extends('layouts.font-end')
@section('content')
    <script>
        let options;
        let date_type;
        $(document).ready(function () {
            options = '{!! $options !!}';
            date_type = '{!! $date_type !!}';
            options = JSON.parse(options);
            $.each(options, function (index, value) {
                let group_id = 'group-' + index;
                $("#campaign_option").append('' +
                    '<div class="option"><h5 class="page-title">' + (value['require'] ? '<span style="color: red">*</span>' : '') + value['name'] + '</h5>' +
                    '<div id="' + group_id + '" class="form-group row"></div></div>' +
                    '');
                $.each(value['select'], function (index2, value2) {
                    let price = date_type === 1 ? value2['weekday_price'] : value2['holiday_price'];
                    price = price ? price : 0;
                    let check = value2['status'] === 1 ? 'checked' : '';
                    let input_option = '<input value="' + index2 + '" name="option_' + index + '" class="form-control checkbox" type="radio"' + check + '/>';
                    if (value['type'] === '1') {
                        input_option = '<input name="option_' + index + '_' + index2 + '" class="form-control checkbox" type="checkbox" ' + check + '/>';
                    }

                    $('#' + group_id).append('' +
                        '<div class="col-sm-2 mb-2"><label class="">' + value2['name'] + '</label></div>' +
                        '<div class="form-group mb-2 col-sm-10">' +
                        '<div class="div-sub-price">' +
                        '<input class="form-control max-w-200 option-price disabled" type="text" value="' + price + '"/><span>円</span></div>' +
                        input_option + '</div>' +
                        '');
                });
            });
            $("#form").validate({
                rules: {
                    user_name: "required",
                    user_phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 11
                    },
                    user_email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    user_name: "名前入力してください。",
                    user_phone: {
                        required: "電話番号を入力してください。",
                        number: "電話番号を入力してください。",
                        minlength: "電話番号は10桁で指定してください。",
                        maxlength: "電話番号は10桁で指定してください。"
                    },
                    user_email: {
                        required: "メールを入力してください。",
                        email: "メールフォーマットを入力してください。"
                    }
                }
            });
        });

        function checkSubmit() {
            let list_option = '';
            let price = 0;
            let show_message_require = false;
            $.each(options, function (index, value) {
                let flag_check = false;
                $.each(value['select'], function (index2, value2) {
                    let name = 'option_' + index + '_' + index2;
                    let name_radio = 'option_' + index;
                    let checked = $('input[name="' + name + '"]:checked').length;
                    let radio = $('input[name="' + name_radio + '"]:checked').val() == index2;
                    if (value['type'] === '1') {
                        options[index]['select'][index2]['status'] = checked ? 1 : 0;
                    } else {
                        options[index]['select'][index2]['status'] = radio ? 1 : 0;
                    }

                    if (checked || radio) {
                        let iprice = date_type === 1 ? value2['weekday_price'] : value2['holiday_price'];
                        if (!iprice || iprice === '') {
                            iprice = 0;
                        }
                        price = price + parseInt(iprice);
                        let coma = list_option == '' ? '' : ', ';
                        list_option = list_option + coma + value2['name'];
                        flag_check = true;
                    }

                });

                if (value['require']) {
                    if (!flag_check) {
                        show_message_require = true;
                    }
                }
            });

            if (list_option) {
                $('#content').val(JSON.stringify(options));
                if (show_message_require) {
                    $('#require_empty').show();
                    $('#none_opiton').hide();
                } else {
                    $('#submit').click();
                    $('#none_opiton').hide();
                    $('#require_empty').hide();
                }

            } else {
                $('#none_opiton').show();
            }
        }
    </script>
    <div id="app">
        <div class="container p-5 mt-3">
            <form class="p-3" method="POST" action="{{ route('plan.update') }}" id="form">
                @csrf
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
                        <td>
                            @if($plan['status'] == 1 || $plan['status'] == 2)
                                <select name="time" class="form-control">
                                    <option value="">{{ $plan['start_time'] }} - {{ $plan['end_time'] }}</option>
                                    @foreach($list_data as $key => $item)
                                        <option value="{{ $item['id'] }}">{{ $item['time_start'] }}
                                            - {{ $item['time_end'] }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $plan['start_time'] }} - {{ $plan['end_time'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>お名前</td>
                        <td>
                            @if($plan['status'] == 1 || $plan['status'] == 2)
                                <input type="text" class="form-control" name="user_name"
                                       value="{{ $plan['user_name'] }}"/>
                            @else
                                {{ $plan['user_name'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>電話番号</td>
                        <td>
                            @if($plan['status'] == 1 || $plan['status'] == 2)
                                <input type="text" class="form-control" name="user_phone"
                                       value="{{ $plan['user_phone'] }}"/>
                            @else
                                {{ $plan['user_phone'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>メールアドレス</td>
                        <td>
                            @if($plan['status'] == 1 || $plan['status'] == 2)
                                <input type="text" class="form-control" name="user_email"
                                       value="{{ $plan['user_email'] }}"/>
                            @else
                                {{ $plan['user_email'] }}
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="col-sm-12">
                    <div class="mt-3 mb-4">
                        <h5>{!!trans('calendar.subtitle_2_1')!!}</h5>
                        <h5>{!!trans('calendar.subtitle_2_2')!!}</h5>
                        <h5>{!!trans('calendar.subtitle_2_3')!!}</h5>
                        <div id="campaign_option" class="mt-4"></div>
                        <span style="display: none" class="alert alert-danger"
                              id="none_opiton">{!!trans('calendar.option_none')!!}</span>
                        <span style="display: none" class="alert alert-danger"
                              id="require_empty">{!!trans('calendar.require_empty')!!}</span>
                    </div>
                    @if($plan['status'] == 1 || $plan['status'] == 2)
                        <input id="content" class="form-control" type="hidden" name="content"/>
                        <input class="form-control" type="hidden" name="token" value="{{ $plan['token'] }}"/>
                        <button class="btn btn-primary btn-lg pl-5 pr-5" type="button"
                                onclick="checkSubmit()">{!!trans('calendar.submit')!!}</button>
                        <button id="submit" type="submit" style="display: none"></button>
                        @if($errors->any())
                            <div class="alert alert-success mt-2">{{$errors->first()}}</div>
                        @endif
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
