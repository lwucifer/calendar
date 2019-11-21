@extends('frontend.booking')

@section('calendar')
    <div class="row justify-content-md-center m-0">
        <div class="mb-5 mt-2">
            <div class="hidden">
                <a class="call-store" href="tel:{{$phone}}"></a>
            </div>

            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        body {
            overflow: hidden;
            background-color: #eee
        }

        input {
            padding: 0 0.375rem
        }

        .w-400 {
            width: 403px;
            max-width: 100%
        }

        .w-200 {
            width: 200px;
            max-width: 100%
        }

        .max-w-500 {
            max-width: 500px
        }

        body {
            background-color: #f9efe3;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9;
        }

        .table-custom td {
            padding-left: .5rem;
            padding-right: .5rem;
        }

        .table-custom td:first-child {
            padding-left: 0;
            width: 120px;
        }

        #table-times > li >span{
            font-size: 1.2rem;
        }
        #table-times > li {
            padding: 10px 0;
            text-align: center;
            line-height: 2rem;
            border-bottom: 1px solid #ddd;
        }

        .btn-time {
            display: inline-block;
            padding: 0 30px;
            margin-left: 15px;
            border-radius: 34px;
            transition: all 200ms;
            color: #fff;
            background: #ef7ca7;
            border: 2px solid #ef7ca7;
        }

        .btn-near {
            display: inline-block;
            padding: 0 30px;
            margin-left: 15px;
            border-radius: 34px;
            transition: all 200ms;
            color: #fff;
            background: #f4ccc1;
            border: 2px solid #f4ccc1;
        }

        .btn-near:hover,
        .btn-near:active,
        .btn-time:hover,
        .btn-time:active {
            background-color: #d07cef;
            border-color: #d07cef;
            outline: none;
        }

        .btn-near.active,
        .btn-time.active {
            background-color: #d07cef;
            border-color: #d07cef;
        }

        .btn-time.disabled {
            cursor: default;
            background: #fff;
            border-color: #ddd;
            color: #aaa;
        }

        span.btn-time {
            background: #fff;
            font-size: 0;
            border-color: #ccc;
            color: #aaa;
        }

        .btn {
            cursor: pointer;
            color: #fff !important;
        }

        .to-step2.disabled {
            background-color: #aaa;
            border-color: #aaa;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .alert-danger {
            display: block;
            padding: 0.375rem 0.75rem;
        }

        .form-control.disabled {
            cursor: default;
            pointer-events: none;
            background-color: #efefef;
        }

        .form-control.checkbox {
            width: 1rem;
            margin-left: 1rem;
        }

        span.form-control {
            background-color: #efefef;
        }

        .sub-phone {
            position: absolute;
            padding: .375rem 0.5rem;
            top: 3px;
            z-index: 9;
        }

        .sub-phone + .form-control {
            padding-left: 39px;
        }

        .sub-price {
            position: absolute;
            padding: .375rem 0.5rem;
            top: 3px;
            left: auto;
            right: 8px;
            z-index: 9;
        }

        .div-sub-price {
            position: relative;
            display: inline-block;
        }

        .div-sub-price > span {
            position: absolute;
            left: auto;
            right: 8px;
            top: 8px;
        }

        .sub-phone + .form-control {
            padding-right: 35px;
        }

        .form-group {
            align-items: center;
        }

        .fc-row .fc-bgevent-skeleton td, .fc-row .fc-highlight-skeleton td {
            text-align: center;
        }

        .fc-sat {
            color: #1f6fb2;
            background-color: transparent;
        }

        .fc-sun {
            color: brown;
            background-color: transparent;
        }

        #calendar .fc-view-container table .fc-body,
        .fc-unthemed td.fc-today {
            background-color: #B5B5B5;
        }

        #calendar {
            cursor: pointer;
        }

        .title-fullcalendar {
            color: #000 !important;
            font-weight: 500;
            text-align: center;
            opacity: 1;
        }
        .title-fullcalendar.text-info {
            color: #b65980 !important;
        }

        .fc-bgevent {
            opacity: .8 !important;
        }

        .fc-unthemed .fc-content,
        .fc-unthemed .fc-divider,
        .fc-unthemed .fc-list-heading td,
        .fc-unthemed .fc-list-view,
        .fc-unthemed .fc-popover,
        .fc-unthemed .fc-row,
        .fc-unthemed tbody,
        .fc-unthemed td,
        .fc-unthemed th,
        .fc-unthemed thead {
            border-color: #ccc;
        }

        .form-control[type="checkbox"] {
            height: 1.5rem;
        }

        #user_name-error, #phone-error, #email-error {
            margin-top: 2px;
            color: #761b18;
            background-color: #f9d6d5;
            border-color: #f7c6c5;
            padding: 10px 10px 10px 10px;
        }
        .error {

        }
        .fc-ltr .fc-basic-view .fc-day-top .fc-day-number{
            float: none;
            margin: 0 auto;
            text-align: center;
        }
        #calendar .fc-view-container table .fc-head{
            background-color: #efefef;
        }
        #calendar .fc-view-container table .fc-body, .fc-unthemed td.fc-today{
            background-color: #f7f7f7;
        }
        .fc-bgevent {
            background-color: #fff !important;
        }
        .fc-row .fc-bgevent-skeleton td, .fc-row .fc-highlight-skeleton td{
            border-color: #ccc !important;
        }
        .fc-toolbar.fc-header-toolbar {
            margin-bottom: 0;
        }
        .fc-state-default {
            background-image: none;
            border: 1px solid #ccc;
            color: #555;
        }
        .fc-bgevent {
            font-size: 1.2rem;
            line-height: 1.25;
            vertical-align: middle !important;
            padding: 0 !important;
        }
        .fc-ltr .fc-basic-view .fc-day-top .fc-day-number {
            font-size: 1.2rem;
            font-weight: 300;
        }
        .fc-day-number,
        .fc-past .fc-day-number {
            color: transparent !important;
        }
        #calendar {
            background: #fff;
            padding: 1rem;
            border-radius: 1rem;
        }
        .times {
            max-width: 750px;
            margin: 0 auto;
        }
        #table-times {
            background: #fff;
            padding: 1.5rem 2rem 2rem;
            border-radius: 1rem;
        }
        .fc .fc-day-header {
            padding: 2px 0;
            font-size: 1.2rem;
            font-weight: 500;
        }
        .fc-button {
            border-radius: 0 !important;
            box-shadow: none;
        }

        @media (max-width: 767px) {
            .fc-bgevent {
                font-size: 0.875rem;
            }
            .title-fullcalendar {
                font-size: 0.75rem;
            }
            .fc-toolbar h2 {
                font-size: 1.5rem;
            }
            .fc .fc-day-header {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .btn-time {
                margin-left: 0;
                padding: 0 15px;
            }
            #table-times {
                padding: 0.5rem 0.5rem 1.5rem;
            }
            label {
                margin-bottom: 0;
                line-height: 1;
            }
            .w-400 {
                width: auto;
            }
            .table-custom tr>td {
                padding: 0;
            }
            #table-times > li >span {
                font-size: 1rem;
                padding: 0 15px;
            }
        }

        section {
            overflow: auto;
            padding-top: 20px;
            padding-bottom: 35px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        section > .container >div{
            max-width: 900px;
            margin: 0 auto;
        }
        section >.container >.box{
            background: #fff;
            border-radius: 1rem;
            padding: 1rem;
        }

        @media (max-width: 400px) {
            .title-fullcalendar {
                font-size: 0.6rem;
            }
            #table-times > li {
                padding: 0px 0 10px;
            }
        }
        .fc-scroller.fc-day-grid-container {
            height: auto !important;
        }
    </style>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var campaign_code;
        var plan_list;
        var array_time;
        var dataBookJson;

        function getCalendar(event) {
            var url = window.location.href;
            var campaign = url.split("/", 5).pop();
            let store_code = $('#switch_store').val();
            let restURL = "/calendar/" + campaign + '/' + store_code;
            window.location.href = restURL;
        }

        function setCalendar(parseJson2, dateTel2) {
            return {
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                dayClick: function (date) {
                    if (array_time.indexOf(date.format('YYYY-MM-DD')) !== -1) {
                        toTimes(dataBookJson[date.format('YYYY-MM-DD')]);
                        let MyDateString = date.format('YYYY-MM-DD');
                        clearAllBackgroundDay(array_time);
                        $('.fc-day[data-date=' + MyDateString + ']').css({
                            "backgroundColor": "yellow",
                            "borderBottom": "5px solid #ccc"
                        });
                    }

                    if (dateTel2.indexOf(date.format('YYYY-MM-DD')) !== -1) {
                        var href = $('.call-store').attr('href');
                        window.location.href = href;
                    }
                },
                firstDay: 1,
                events: parseJson2,
                showNonCurrentDates: false,
                filterResourcesWithEvents: true,
                allDaySlot: false,
                resourceGroupField: 'groupId',
                resources: [
                    {
                        id: 'A',
                        groupId: '1',
                        title: 'Resource A'
                    },
                    {
                        id: 'B',
                        groupdId: '1',
                        title: 'Resource B'
                    },
                    {
                        id: 'C',
                        groupId: '2',
                        title: 'Resource C'
                    }
                ],
                eventRender: function (event, element, view) {
                    if (event.rendering == 'background') {
                        element.append(event.title);
                    }
                },
                viewRender: function (view, element) {
                    intervalStart = view.intervalStart;
                    intervalEnd = view.intervalEnd;

                    let length = $('.fc-day-grid .fc-row:last-child .fc-bgevent-skeleton').length;
                    let height = $('.fc-day-grid .fc-row:last-child').height();
                    let old_height = $('.fc-day-grid-container').height();
                    if (length === 0) {
                        $('.fc-day-grid .fc-row:last-child').remove();
                        $('.fc-day-grid-container').height(old_height - height);
                    }
                },
                locale: 'ja'
            }
        }

        $(document).ready(function () {
            smoothScroll($('#step1'));
            const isAdmin = '{!! $is_admin !!}';
            const jsonData = '{!! $calendar !!}';
            const dataBook = '{!! $dataBook !!}';

            const parseJson = JSON.parse(jsonData);
            dataBookJson = JSON.parse(dataBook);
            const firstDayCampaign = parseJson[0] ? moment(parseJson[0].start, 'YYYY-MM-DD') : moment();
            const dateTel = JSON.parse('{!! $dateTel !!}');
            array_time = Object.keys(dataBookJson);
            if (dataBookJson.length > 0) {
                campaign_code = dataBookJson[array_time[0]]['campaign']['code'];
            }
            toTimes(dataBookJson[array_time[0]]);
            let source = setCalendar(parseJson, dateTel);
            $('#calendar').fullCalendar(source);
            $('#calendar').fullCalendar('gotoDate', firstDayCampaign);
            $('.fc-day[data-date=' + array_time[0] + ']').css({
                "backgroundColor": "yellow",
                "borderBottom": "5px solid #ccc"
            });

            if(isAdmin){
                $("#form").validate({
                    rules: {
                        user_name: "required",
                        phone: {
                            required: true,
                            number: true,
                            minlength: 10,
                            maxlength: 11
                        }
                    },
                    messages: {
                        user_name: "名前入力してください。",
                        phone: {
                            required: "電話番号を入力してください。",
                            number: "電話番号を入力してください。",
                            minlength: "電話番号は10桁で指定してください。",
                            maxlength: "電話番号は10桁で指定してください。"
                        }
                    }
                });
            }
            else{
                $("#form").validate({
                    rules: {
                        user_name: "required",
                        phone: {
                            required: true,
                            number: true,
                            minlength: 10,
                            maxlength: 11
                        },
                        email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        user_name: "名前入力してください。",
                        phone: {
                            required: "電話番号を入力してください。",
                            number: "電話番号を入力してください。",
                            minlength: "電話番号は10桁で指定してください。",
                            maxlength: "電話番号は10桁で指定してください。"
                        },
                        email: {
                            required: "メールを入力してください。",
                            email: "メールフォーマットを入力してください。"
                        }
                    }
                });
            }
        });
        var options;
        var date_type;
        var benefits;

        function smoothScroll(target) {
            $('#step1').hide();
            $('#step2').hide();
            $('#step3').hide();
            $(target).show();
            $('body,html').stop().animate(
                {'scrollTop': target.offset().top + 1}, 350
            );

            // clear all error when click back button
            $('#none_opiton').hide();
            $('#phone-error').hide();
            $('#email-error').hide();
            $('#require_empty').hide();
            $('#user_name-error').hide();
        }

        function checkCurrentTime(date, time) {

            let today = new Date();
            let current_time = new Date(date + ' ' + time + ':00');

            // disable select option time if current time is over
            if (today.toLocaleDateString() === current_time.toLocaleDateString()) {
                if (today > current_time) {
                    return false;
                }
            }
            return true;
        }

        function clearAllBackgroundDay(allDate) {
            for (let i = 0; i < allDate.length; i++) {
                $('.fc-day[data-date=' + allDate[i] + ']').css({
                    "background": "none",
                    "borderBottom": "5px solid #ccc"
                });
            }
        }

        function convertDateFormat(date) {
            let v_year = date.getFullYear();
            let v_month = date.getMonth() +1;
            if(v_month < 10 )
            {
                v_month = '0' + v_month;
            }


            let v_date = date.getDate();
            if(v_date < 10 )
            {
                v_date = '0' + v_date;
            }

            return v_year+'-'+v_month+'-'+v_date;
        }

        function getStatus(lane_number, status) {
            if(status/lane_number*100 > 50)
                return 0;
            else
                return 1;
        }

        function toTimes(data) {
            if (!data) {
                return;
            }
            plan_list = data['plan_list'];
            if (!plan_list[0]) {
                $("#table-times").html('');
                return;
            }

            options = data['content'];
            date_type = data['date_type'];
            benefits = data['benefits'];
            $('#date').val(data['date']);
            $('#store').val($('#switch_store').val());
            $('#campaign').val(data['campaign']['code']);
            $('#store_name').val($('#switch_store option:selected').text());
            $('#campaign_name').val(data['campaign']['web_name']);

            let current = new Date(data['date']);
            let today = current.toLocaleDateString('ja-JA', {weekday: 'short'});
            let days = ['sun', 'mon', 'fri', 'thu', 'wed', 'tue', 'sat'];
            let specialWeekend = 'fc-' + days[current.getDay()];

            $('#code_temp').val(benefits);
            $('#code').val(benefits);
            $("#idate").html('');
            $("#idate").html(current.getMonth() + 1 + '月' + current.getDate() + '日' + '(<span class="' + specialWeekend + '">' + today + '</span>)');
            $("#table-times").html('');

            let end = plan_list.length - 1;
            var valuetime = plan_list[0]['time_start'];
            var valueend = plan_list[end]['time_end'];
            var mstarttime = (new Date("01/01/2030 " + valuetime)).getMinutes();
            var hstarttime = (new Date("01/01/2030 " + valuetime)).getHours();
            var mendtime = (new Date("01/01/2030 " + valueend)).getMinutes();
            var current_lane_number = data['lane_number'];

            $.each(plan_list, function (index, value) {
                let status = getStatus(current_lane_number, value['status']);
                let time = "'" + data['date'] + "','" + value['time_start'] + "','" + value['time_end'] + "', this";
                let format = 'HH:mm';
                let start_minute = moment("01/01/2030 " + value['time_start']).format(format);
                let end_minute = moment("01/01/2030 " + value['time_end']).format(format);
                let html = `<li class="col-6"><span>${start_minute}</span><button data-placement="top" data-toggle="tooltip" class="btn-time" title="予約" onclick="toOptions(${time})">〇予約する</button></li>`;
                let html_disable = `<li class="col-6"><span>${start_minute}</span><div class="btn-time disabled" >空き枠なし</div></li>`;
                let html_2 = `<li class="col-6"><span>${start_minute}</span><button class="btn-time btn-near" onclick="toOptions(${time})">△予約する</button></li>`;

                let current_html = getStatus(current_lane_number, value['status']) === 0 ? html : html_2;
                if (!checkCurrentTime(data['date'], value['time_start'])) {
                    $("#table-times").append(html_disable);
                } else {
                    $("#table-times").append((value['status'] > 0) ? current_html : html_disable);
                }

            });
            if (plan_list.length % 2 !== 0) {
                $("#table-times").append(`<li class="col-6"></li>`);
            }

            $("#campaign_option").html('');
            $.each(options, function (index, value) {
                let group_id = 'group-' + index;
                $("#campaign_option").append('' +
                '<div class="option"><h5 class="page-title">' + (value['require'] ? '<span style="color: red">*</span>' : '') + value['name'] + '</h5>' +
                    '<div id="' + group_id + '" class="form-group row"></div></div>' +
                    '');
                $.each(value['select'], function (index2, value2) {
                    let price = date_type === 1 ? value2['weekday_price'] : value2['holiday_price'];
                    price = price ? price : 0;

                    let input_option = '<input value="' + index2 + '" name="option_' + index + '" class="form-control checkbox" type="radio"/>';
                    if (value['type'] === '1') {
                        input_option = '<input name="option_' + index + '_' + index2 + '" class="form-control checkbox" type="checkbox"/>';
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
        }

        function toOptions(date, start_time, end_time, el) {
            if (plan_list) {
                $('#select_time').html('');
                $.each(plan_list, function (index, value) {
                    let format = 'HH:mm';
                    let start_minute = moment("01/01/2030 " + value['time_start']).format(format);
                    let end_minute = moment("01/01/2030 " + value['time_end']).format(format);
                    let html = `<option value=${index} ${start_time === value['time_start'] ? 'selected' : ''}>${start_minute} - ${end_minute}</option>`;

                    if (checkCurrentTime(date, value['time_start'])) {
                        $('#select_time').append((value['status'] > 0) ? html : '');
                    }

                });
            }
            if ($('#code_temp').val() == benefits || $('#code_temp').val() == '') {
                // $('#code').val($('#code_temp').val());
                smoothScroll($('#step2'));
                $('#start_time').val(start_time);
                $('#end_time').val(end_time);
                $('#code_error').hide();
            } else {
                $('#code_error').show();
            }

            $('.checkbox').prop('checked', false);

            $('.btn-time').removeClass('active');
            $(el).addClass('active');
        }

        function selectTime(index) {
            let dataTime = plan_list[index];
            $('#start_time').val(dataTime['time_start']);
            $('#end_time').val(dataTime['time_end']);
        }

        function toConfirm() {
            let list_option = '';

            // get base fee of campaign
            let current_date = new Date($('#date').val());
            let base_price = dataBookJson[convertDateFormat(current_date)]['price'];
            if (!base_price || base_price === '') {
                base_price = 0;
            }

            let price =  parseInt(base_price);

            // let benefit = $('#code_temp').val();
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

            $('#price').val(price);
            if (list_option) {
                $('#list_option').html(list_option);
                $('#content').val(JSON.stringify(options));
                if (show_message_require){
                    $('#require_empty').show();
                    $('#none_opiton').hide();
                }else {
                    smoothScroll($('#step3'));
                    $('#none_opiton').hide();
                    $('#require_empty').hide();
                }

            } else {
                $('#none_opiton').show();
            }
        }

        function checkSubmit() {
            $('#submit').click();
            $('#select_time').prop('disabled', 'disabled');
            if ($("#form").valid()) {
                $('.btn-final').attr('disabled', 'disabled');
            }
        }
    </script>
@endsection
