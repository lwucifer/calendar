<h3>@lang('calendar.mail.subject')</h3>

<div>
    <label class="left float-left">{!!trans('calendar.plan_name')!!}:</label>
    <span class="right float-left">{{ $name }}</span><br>
    <label class="left float-left">{!!trans('calendar.store')!!}:</label>
    <span class="right float-left">{{ $store_id }}</span><br>
    <label class="left float-left">{!!trans('calendar.date')!!}:</label>
    <span class="right float-left">{{ $date }}</span><br>
    <label class="left float-left">{!!trans('calendar.start_time')!!}:</label>
    <span class="right float-left">{{ $start_time }}</span><br>
    <label class="left float-left">{!!trans('calendar.end_time')!!}:</label>
    <span class="right float-left">{{ $end_time }}</span><br><br>
    <label>{!!trans('calendar.mail.url')!!}:</label>
    <a href="{{ url('/confirm/'.$token) }}">{{ $name }}</a><br>
    <p>24時間以内にメール記載のURLより確定をお願いします<br>24時間経過しても予約が確定いただけない場合、自動的に仮予約はキャンセルとなります。</p>

    <div>{!! nl2br($signStore) !!}</div>
</div>
