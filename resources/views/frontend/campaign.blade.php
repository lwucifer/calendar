@extends('layouts.font-end')

@section('content')
    <div class="container main pr-5 pl-5 pb-5">
        <div class="p-3 text-left">
            <h2>{!!trans('calendar.list_campaign')!!}</h2>
            <hr>
            <div class="form-group justify-content-end mt-3">
                <input type="text" name="query" id="query" class="form-control max-w-400 mb-3 mr-3"
                       placeholder="{!!trans('calendar.search_placeholder')!!}"/>
                <button id="submit" type="button" class="btn btn-primary mb-3">{!!trans('calendar.back')!!}</button>
            </div>
            <ul id="campaignList" class="row mt-4">
                @foreach($listData as $campaign)
                    <li class="col-md-3 col-sm-4 col-6 mb-3">
                        <a href="./calendar/{{ $campaign['campaign_code'] }}/{{ $campaign['store_code'] }}">
                            <h5 class="title-campaign {{ $campaign['new'] ? 'new' : '' }} {{ $campaign['last_update'] ? 'update' : '' }}">{{ $campaign['name'] }}
                            </h5>
                            @if($campaign['new'])
                                <span class="new-icon">{!!trans('calendar.new')!!}</span>
                            @endif
                            @if($campaign['last_update'])
                                <span class="new-icon update">{!!trans('calendar.update')!!}</span>
                            @endif
                            <img class="img-responsive" src="{{ url('/images/default.jpg') }}"/>
                        </a>
                    </li>
                @endforeach
            </ul>
            <hr>
            <div class="form-group justify-content-end mt-3">
                {!!  $items->links() !!}
            </div>
        </div>
    </div>
    <script>
        var _token = $('input[name="_token"]').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function search() {
            var campaign_id = $('#switch_campaign').val();
            var store_id = $('#switch_store').val();
            $.ajax({
                url: "{{ route('searchCampaign') }}",
                method: "POST",
                data: {campaign_id: campaign_id, store_id: store_id, _token: _token},
                success: function (data) {
                    $('#campaignList').fadeIn();
                    $('#campaignList').html(data);
                }
            });
        }

        $(document).ready(function () {
            $('#submit').click(function () {
                var query = $('#query').val();
                $.ajax({
                    url: "{{ route('searchCampaign') }}",
                    method: "POST",
                    data: {query: query, _token: _token},
                    success: function (data) {
                        $('#campaignList').fadeIn();
                        $('#campaignList').html(data);
                    }
                });
            });
        });
    </script>
    <style>
        .pagination .page-item {
            -webkit-flex-basis: 0;
            flex-basis: 0;
            -webkit-flex-grow: 1;
            flex-grow: 1;
        }
        .pagination {
            width: 100%;
            max-width: 600px;
        }
        .page-item  .page-link {
            padding: .5rem 0rem;
            text-align: center;
        }

        @media (min-width: 992px) {
            .main {
                padding-top: 65px;
            }
        }

        @media (max-width: 991px) {
            .main {
                padding: 1rem !important;
            }
            .navbar {
                position: static;
            }
        }

        @media (max-width: 767px) {
        }

        @media (max-width: 575px) {
            #query {
                max-width: calc(100% - 71px);
            }
        }

        @media (max-width: 400px) {
            .title-fullcalendar {
                font-size: 0.65rem;
            }
        }
    </style>
@endsection
