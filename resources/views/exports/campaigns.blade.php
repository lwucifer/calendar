<table>
    <thead>
        <tr>
            <th>{!!trans('exports.campaign.id')!!}</th>
            <th>{!!trans('exports.campaign.name')!!}</th>
            <th>{!!trans('exports.campaign.web_name')!!}</th>
            <th>{!!trans('exports.campaign.time')!!}</th>
            <th>{!!trans('exports.campaign.photo_id')!!}</th>
            <th>{!!trans('exports.campaign.is_display_calendar')!!}</th>
            <th>{!!trans('exports.campaign.comment')!!}</th>
            <th>{!!trans('exports.campaign.is_enable')!!}</th>
            <th>{!!trans('exports.campaign.code')!!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($campaigns as $campaign)
        <tr>
            <td>{{ $campaign->id }}</td>
            <td>{{ $campaign->name }}</td>
            <td>{{ $campaign->web_name }}</td>
            <td>{{ $campaign->time }}</td>
            <td>{{ $campaign->photo_id }}</td>
            <td>{{ $campaign->is_display_calendar }}</td>
            <td>{{ $campaign->comment }}</td>
            <td>{{ $campaign->is_enable }}</td>
            <td>{{ $campaign->code }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
