@extends('Admin::layout.main')
@section('meta_title', 'QPT Adwords Data')
@section('content')

    <div id="Content_box">
        <div id="content">
            <div style="width: 230px; float:left;">
                @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords', 'tab' => 'qpt'])
            </div>
            <div style="width: 20px; float:left;">&nbsp;</div>
            <div style="width: 980px; float:left;" class="admin">
                <div class="quick-stats" style="border-bottom:0;">
                    <div class="header">
                        QPTs
                    </div>
                </div>

                <br />

                <style>
                    #datatable td { text-align: left; padding-left:6px;}
                    td.orange {
                        background-color:#fff9e0;
                    }

                    .campaigns_view, .last_2_view,.last_3_view, .last_1_view, .spend_data_view {
                        display:none;
                    }

                    .last_2_view_a, .last_1_view_a,.last_3_view_a, .campaigns_view_a  {

                        color:#363636 !important;
                    }

                </style>
                <table style="width:100%" id="datatable" class="stripe">
                    <thead>
                    <tr>
                        <td><h3>Campaign</h3></td>
                        <td><h3>{{ \Carbon\Carbon::now()->subMonths(3)->format('M') }} QPT</h3></td>
                        <td><h3>{{ \Carbon\Carbon::now()->subMonths(2)->format('M') }} QPT</h3></td>
                        <td><h3>{{ \Carbon\Carbon::now()->subMonth()->format('M') }} QPT</h3></td>
                        <td><h3>Optimum</h3></td>
                        <td style="width:15%;"><h3>Spend</h3></td>
                        <td><h3>Revenue</h3></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($qpt as $campaign_id => $data)
                        <tr>
                            <td>
                                {{ $campaigns->where('id', $campaign_id)->first()->name }}
                            </td>
                            <td>
                                <a style="cursor:pointer" class="last_3_view_a" data-i="{{ $campaign_id }}">{{ $data['last_3_month']['qpt'] > 0 ? $data['last_3_month']['qpt'] : '-' }}</a>

                                <div class="last_3_view" data-i="{{ $campaign_id }}">
                                    {{ $data['last_3_month']['quotes'] }} quotes
                                    <br />
                                    {{ $data['last_3_month']['buying_trades'] }} TP
                                    <br />
                                    {{ $data['last_3_month']['diff'] }}% diff
                                    <br />
                                    <br />
                                    (hide)
                                </div>
                            </td>
                            <td>
                                <a style="cursor:pointer" class="last_2_view_a" data-i="{{ $campaign_id }}">{{ $data['last_2_month']['qpt'] > 0 ? $data['last_2_month']['qpt'] : '-' }}</a>

                                <div class="last_2_view" data-i="{{ $campaign_id }}">
                                    {{ $data['last_2_month']['quotes'] }} quotes
                                    <br />
                                    {{ $data['last_2_month']['buying_trades'] }} TP
                                    <br />
                                    {{ $data['last_2_month']['diff'] }}% diff
                                    <br />
                                    <br />
                                    (hide)
                                </div>
                            </td>
                            <td>
                                <a style="cursor:pointer" class="last_1_view_a" data-i="{{ $campaign_id }}">{{ $data['last_1_month']['qpt'] > 0 ? $data['last_1_month']['qpt'] : '-' }}</a>

                                <div class="last_1_view" data-i="{{ $campaign_id }}">
                                    {{ $data['last_1_month']['quotes'] }} quotes
                                    <br />
                                    {{ $data['last_1_month']['buying_trades'] }} TP
                                    <br />
                                    {{ $data['last_1_month']['diff'] }}% diff
                                    <br />
                                    <br />
                                    (hide)
                                </div>
                            </td>
                            <td>
                                {{ $data['optimum'] }}
                            </td>
                            <td>
                                <a style="cursor:pointer" class="spend_data" data-i="{{ $campaign_id }}">£{{ number_format($data['internal_spend'], 2) }}</a>

                                <div class="spend_data_view" data-i="{{ $campaign_id }}">
                                    <strong>For {{ \Carbon\Carbon::now()->subMonth()->format('M') }}</strong>
                                    <br />
                                    Spend: £{{ number_format($data['spend'], 2) }}
                                    <br />
                                    Quotes: {{ number_format($data['quotes']) }}
                                    <br />
                                    Priced: {{ number_format($data['priced']) }}
                                    <br />
                                    CPC: £{{ number_format($data['cpc'], 2) }}
                                    <br />
                                    <br />
                                    (hide)
                                </div>
                            </td>
                            <td>
                                £{{ number_format($data['revenue'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@stop


@section('javascript')
    <script>
        $(document).ready(function() {
            $(".campaigns_view_a").click(function() {
                $(".campaigns_view[data-i="+($(this).data('i'))+"]").show();
            });
            $(".last_1_view_a").click(function() {
                $(".last_1_view[data-i="+($(this).data('i'))+"]").show();
            });
            $(".last_2_view_a").click(function() {
                $(".last_2_view[data-i="+($(this).data('i'))+"]").show();
            });
            $(".last_3_view_a").click(function() {
                $(".last_3_view[data-i="+($(this).data('i'))+"]").show();
            });
            $(".spend_data").click(function() {
                $(".spend_data_view[data-i="+($(this).data('i'))+"]").show();
            });


            $(".spend_data_view").click(function() {
                $(".spend_data_view[data-i="+($(this).data('i'))+"]").hide();
            });

            $(".campaigns_view").click(function() {
                $(".campaigns_view[data-i="+($(this).data('i'))+"]").hide();
            });

            $(".last_1_view").click(function() {
                $(".last_1_view[data-i="+($(this).data('i'))+"]").hide();
            });
            $(".last_2_view").click(function() {
                $(".last_2_view[data-i="+($(this).data('i'))+"]").hide();
            });
            $(".last_3_view").click(function() {
                $(".last_3_view[data-i="+($(this).data('i'))+"]").hide();
            });


            $('#datatable').dataTable( {
                "orderClasses": false,
                "order": [[ 0, "asc" ]],
                "iDisplayLength": 50,
                "bLengthChange": false,
                "bFilter" : false
            });
        } );
    </script>
@stop
