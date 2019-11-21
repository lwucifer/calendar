<h3>@lang('calendar.mail.subject')</h3>

<div>
    <h5>あなたの予約スケジュールは確認されました。ありがとう。</h5>
    <label class="left float-left">{!!trans('calendar.plan_name')!!}:</label>
    <span class="right float-left">{{ $name }}</span><br>
    <label class="left float-left">{!!trans('calendar.date')!!}:</label>
    <span class="right float-left">{{ $date }}</span><br>
    <label class="left float-left">{!!trans('calendar.start_time')!!}:</label>
    <span class="right float-left">{{ $start_time }}</span><br>
    <label class="left float-left">{!!trans('calendar.end_time')!!}:</label>
    <span class="right float-left">{{ $end_time }}</span><br><br>
</div>
