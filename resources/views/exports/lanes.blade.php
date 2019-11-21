<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>order</th>
            <th>weekday_start_time</th>
            <th>weekday_end_time</th>
            <th>holiday_start_time</th>
            <th>holiday_end_time</th>
            <th>visit_time</th>
            <th>store_id</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lanes as $lane)
        <tr>
            <td>{{ $lane->id }}</td>
            <td>{{ $lane->name }}</td>
            <td>{{ $lane->order }}</td>
            <td>{{ $lane->weekday_start_time }}</td>
            <td>{{ $lane->weekday_end_time }}</td>
            <td>{{ $lane->holiday_start_time }}</td>
            <td>{{ $lane->holiday_end_time }}</td>
            <td>{{ $lane->visit_time }}</td>
            <td>{{ $lane->store_id }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
