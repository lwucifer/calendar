<table>
    <thead>
        <tr>
            <th>{!!trans('exports.store.id')!!}</th>
            <th>{!!trans('exports.store.name')!!}</th>
            <th>{!!trans('exports.store.phone')!!}</th>
            <th>{!!trans('exports.store.manager_id')!!}</th>
            <th>{!!trans('exports.store.sign_email')!!}</th>
            <th>{!!trans('exports.store.comment')!!}</th>
            <th>{!!trans('exports.store.weekday_start_time')!!}</th>
            <th>{!!trans('exports.store.weekday_end_time')!!}</th>
            <th>{!!trans('exports.store.holiday_start_time')!!}</th>
            <th>{!!trans('exports.store.holiday_end_time')!!}</th>
            <th>{!!trans('exports.store.day_off_monday')!!}</th>
            <th>{!!trans('exports.store.day_off_tuesday')!!}</th>
            <th>{!!trans('exports.store.day_off_wednesday')!!}</th>
            <th>{!!trans('exports.store.day_off_thursday')!!}</th>
            <th>{!!trans('exports.store.day_off_friday')!!}</th>
            <th>{!!trans('exports.store.day_off_saturday')!!}</th>
            <th>{!!trans('exports.store.day_off_sunday')!!}</th>
            <th>{!!trans('exports.store.fixed_days_off')!!}</th>
            <th>{!!trans('exports.store.fixed_days_on')!!}</th>
            <th>{!!trans('exports.store.is_enable')!!}</th>
            <th>{!!trans('exports.store.code')!!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stores as $store)
        <tr>
            <td>{{ $store->id }}</td>
            <td>{{ $store->name }}</td>
            <td>{{ $store->phone }}</td>
            <td>{{ $store->manager_id }}</td>
            <td>{{ $store->sign_email }}</td>
            <td>{{ $store->comment }}</td>
            <td>{{ $store->weekday_start_time }}</td>
            <td>{{ $store->weekday_end_time }}</td>
            <td>{{ $store->holiday_start_time }}</td>
            <td>{{ $store->holiday_end_time }}</td>
            <td>{{ $store->day_off_monday }}</td>
            <td>{{ $store->day_off_tuesday }}</td>
            <td>{{ $store->day_off_wednesday }}</td>
            <td>{{ $store->day_off_thursday }}</td>
            <td>{{ $store->day_off_friday }}</td>
            <td>{{ $store->day_off_saturday }}</td>
            <td>{{ $store->day_off_sunday }}</td>
            <td>{{ $store->fixed_days_off }}</td>
            <td>{{ $store->fixed_days_on }}</td>
            <td>{{ $store->is_enable }}</td>
            <td>{{ $store->code }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
