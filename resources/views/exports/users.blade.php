<table>
    <thead>
        <tr>
            <th>{!!trans('exports.user.id')!!}</th>
            <th>{!!trans('exports.user.username')!!}</th>
            <th>{!!trans('exports.user.email')!!}</th>
            <th>{!!trans('exports.user.first_name')!!}</th>
            <th>{!!trans('exports.user.last_name')!!}</th>
            <th>{!!trans('exports.user.kana_first_name')!!}</th>
            <th>{!!trans('exports.user.kana_last_name')!!}</th>
            <th>{!!trans('exports.user.store_id')!!}</th>
            <th>{!!trans('exports.user.role_id')!!}</th>
            <th>{!!trans('exports.user.phone')!!}</th>
            <th>{!!trans('exports.user.zip_code')!!}</th>
            <th>{!!trans('exports.user.comment')!!}</th>
            <th>{!!trans('exports.user.parent_user')!!}</th>
            <th>{!!trans('exports.user.is_enable')!!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td>{{ $user->kana_first_name }}</td>
            <td>{{ $user->kana_last_name }}</td>
            <td>{{ $user->store_id }}</td>
            <td>{{ $user->role_id }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->zip_code }}</td>
            <td>{{ $user->comment }}</td>
            <td>{{ $user->parent_user }}</td>
            <td>{{ $user->is_enable }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
