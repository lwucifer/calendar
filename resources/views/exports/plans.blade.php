<table>
    <thead>
        <tr>
            <th>{!!trans('exports.plan.id')!!}</th>
            <th>{!!trans('exports.plan.store_id')!!}</th>
            <th>{!!trans('exports.plan.campaign_id')!!}</th>
            <th>{!!trans('exports.plan.user_id')!!}</th>
            <th>{!!trans('exports.plan.date')!!}</th>
            <th>{!!trans('exports.plan.start_time')!!}</th>
            <th>{!!trans('exports.plan.end_time')!!}</th>
            <th>{!!trans('exports.plan.comment')!!}</th>
            <th>{!!trans('exports.plan.status')!!}</th>
            <th>{!!trans('exports.plan.is_enable')!!}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($plans as $plan)
        <tr>
            <td>{{ $plan->id }}</td>
            <td>{{ $plan->store_id }}</td>
            <td>{{ $plan->campaign_id }}</td>
            <td>{{ $plan->user_id }}</td>
            <td>{{ $plan->date }}</td>
            <td>{{ $plan->start_time }}</td>
            <td>{{ $plan->end_time }}</td>
            <td>{{ $plan->comment }}</td>
            <td>{{ $plan->status }}</td>
            <td>{{ $plan->is_enable }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
