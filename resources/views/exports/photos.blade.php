<table>
    <thead>
        <tr>
            <th>{!!trans('exports.photo.id')!!}</th>
            <th>{!!trans('exports.photo.name')!!}</th>
            <th>{!!trans('exports.photo.cash_id')!!}</th>
            <th>{!!trans('exports.photo.comment')!!}</th>
            <th>{!!trans('exports.photo.is_enable')!!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($photos as $photo)
        <tr>
            <td>{{ $photo->id }}</td>
            <td>{{ $photo->name }}</td>
            <td>{{ $photo->cash_id }}</td>
            <td>{{ $photo->comment }}</td>
            <td>{{ $photo->is_enable }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
