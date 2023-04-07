@extends('Admin::layout.main')

@section('meta_title', 'Tradespeople Quotes')

@section('content')

    <div id="Content_box">
        <div id="wide">
            <div class="margin">

                <div style="width: 15%; float:left;">
                    @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords'])
                </div>
                <div style="width: 2%; float:left;">&nbsp;</div>
                <div style="width: 83%; float:left;" class="admin">

                    <style>
                        table td { text-align: left; padding: 2px 10px; line-height:30px;}
                        table thead td {     background-color: #FBFBFB; }
                        th, td { white-space: nowrap; }
                        div.dataTables_wrapper {
                            margin: 0 auto;
                        }

                    </style>


                    <form method="post" action="{{ route('admin_adwords_keywords') }}">
                        {!! csrf_field() !!}
                        <div class="quick-stats" style="border-bottom:0;">
                            <div class="header">
                                Adwords Keywords

                                <div class="input">
                                    <i class="icon icon-calendar"></i>
                                    <input type="text" id="date-range" name="range" style="display: none;">
                                    <input type="submit" value="Go">
                                </div>
                            </div>
                        </div>

                        <div class="quick-stats" style="border-top:0;">
                            <div class="stats-box">
                                <div class="general">
                                    Spend
                                    <br>
                                    <select name="spend[filter]" style="width:100px !important;">
                                        <option value=">="{{ $filters['spend']['filter'] == '>=' ? ' selected' : '' }}>at least</option>
                                        <option value="<="{{ $filters['spend']['filter'] == '<=' ? ' selected' : '' }}>at most</option>
                                    </select>
                                    <input name="spend[value]" type="text" value="{{ $filters['spend']['value'] }}" placeholder="Spend" style="width:100px !important;margin-bottom:18px;    background-color: #fff;min-width:100px !important;">
                                </div>

                                <div class="general">
                                    Clicks
                                    <br>
                                    <select name="clicks[filter]" style="width:100px !important;">
                                        <option value=">="{{ $filters['clicks']['filter'] == '>=' ? ' selected' : '' }}>at least</option>
                                        <option value="<="{{ $filters['clicks']['filter'] == '<=' ? ' selected' : '' }}>at most</option>
                                    </select>
                                    <input name="clicks[value]" type="text" value="{{ $filters['clicks']['value'] }}" placeholder="Clicks" style="width:100px !important;margin-bottom:18px;    background-color: #fff;min-width:100px !important;">
                                </div>

                                <div class="general">
                                    Bid
                                    <br>
                                    <select name="bids[filter]" style="width:100px !important;">
                                        <option value=">="{{ $filters['bids']['filter'] == '>=' ? ' selected' : '' }}>at least</option>
                                        <option value="<="{{ $filters['bids']['filter'] == '<=' ? ' selected' : '' }}>at most</option>
                                    </select>
                                    <input name="bids[value]" type="text" value="{{ $filters['bids']['value'] }}" placeholder="Bid" style="width:100px !important;margin-bottom:18px;    background-color: #fff;min-width:100px !important;">
                                </div>

                                <div class="general">
                                    Quotes
                                    <br>
                                    <select name="quotes[filter]" style="width:100px !important;">
                                        <option value=">="{{ $filters['quotes']['filter'] == '>=' ? ' selected' : '' }}>at least</option>
                                        <option value="<="{{ $filters['quotes']['filter'] == '<=' ? ' selected' : '' }}>at most</option>
                                    </select>
                                    <input name="quotes[value]" type="text" value="{{ $filters['quotes']['value'] }}" placeholder="Quotes" style="width:100px !important;margin-bottom:18px;    background-color: #fff;min-width:100px !important;">
                                </div>

                                <div class="general last">
                                    Ordering
                                    <br>
                                    <select name="ordering[column]" style="width:100px !important;">
                                        @foreach(['spend', 'clicks', 'quotes', 'quotes_commission', 'quotes_priced', 'quotes_partnered', 'sales', 'sales_commission'] as $column)
                                        <option value="{{ $column }}"{{ $filters['ordering']['column'] == $column ? ' selected' : '' }}>{{ $column }}</option>
                                        @endforeach
                                    </select>
                                    <select name="ordering[direction]" style="width:100px !important;">
                                        <option value="asc"{{ $filters['ordering']['direction'] == 'asc' ? ' selected' : '' }}>asc</option>
                                        <option value="desc"{{ $filters['ordering']['direction'] == 'desc' ? ' selected' : '' }}>desc</option>
                                    </select>
                                </div>

                                <div class="general last">

                                </div>
                                &nbsp;
                            </div>
                        </div>

                    </form>

                    <br />

                    <table style="width:100%;display: block;
        overflow-x: auto;" id="datatable">
                        <thead>
                        <tr>
                            <td style="width:300px;"><h3>ID</h3></td>
                            <td style="width:250px !important;"><h3>Keyword</h3></td>
                            <td><h3>Campaign</h3></td>
                            <td><h3>Adgroup</h3></td>
                            <td><h3>Added</h3></td>
                            <td><h3>Clicks</h3></td>
                            <td><h3>Spend</h3></td>
                            <td><h3>Bid</h3></td>
                            <td><h3>Paying</h3></td>
                            <td><h3>Quotes</h3></td>
                            <td><h3>Partnered</h3></td>
                            <td><h3>Priced</h3></td>
                            <td><h3>Sales</h3></td>
                            <td><h3>Partner</h3></td>
                            <td><h3>Sales</h3></td>
                            <td><h3>Total</h3></td>
                            <td><h3>P/L</h3></td>
                            <td><h3>ROI</h3></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($keywords as $keyword)
                            <tr>
                                <td>
                                    {{ $keyword->keyword_id }}
                                </td>
                                <td>
                                    {{ $keyword->text }}
                                </td>
                                <td>
                                    {{ $keyword->campaign_name }}
                                </td>
                                <td>
                                    {{ $keyword->adgroup_name }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($keyword->created_at)->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ number_format($keyword->clicks) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->spend/1000000, 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->cpcbid/1000000, 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->clicks > 0 ? $keyword->spend/1000000/$keyword->clicks : 0, 2) }}
                                </td>
                                <td>
                                    {{ number_format($keyword->quotes) }}
                                </td>
                                <td>
                                    {{ number_format($keyword->quotes_partnered) }}
                                </td>
                                <td>
                                    {{ number_format($keyword->quotes_priced) }}
                                </td>
                                <td>
                                    {{ number_format($keyword->sales) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->quotes_commission, 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->sales_commission, 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($keyword->quotes_commission+$keyword->sales_commission, 2) }}
                                </td>
                                @if($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000) > 0)
                                    <td class="green">
                                        &pound;{{ number_format($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000), 2) }}
                                    </td>
                                    <td>
                                        {{ number_format(($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000))/($keyword->spend/1000000)*100) }}%
                                    </td>
                                @elseif($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000) == 0)
                                    <td class="yellow">
                                        &pound;0.00
                                    </td>
                                    <td>
                                        {{ number_format(($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000))/($keyword->spend/1000000)*100) }}%
                                    </td>
                                @else
                                    <td class="red">
                                        &pound;{{ number_format(($keyword->quotes_commission+$keyword->sales - ($keyword->spend/1000000)), 2) }}
                                    </td>
                                    <td>
                                        {{ number_format(($keyword->quotes_commission+$keyword->sales_commission - ($keyword->spend/1000000))/($keyword->spend/1000000)*100) }}%
                                    </td>
                                    @endif
                                    </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <br />

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function() {

            $("#date-range").daterangepicker({

                presetRanges: [{
                    text: 'Today',
                    dateStart: function() { return moment() },
                    dateEnd: function() { return moment() }
                }, {
                    text: 'Yesterday',
                    dateStart: function() { return moment().subtract('days', 1) },
                    dateEnd: function() { return moment().subtract('days', 1) }
                }, {
                    text: 'Last 7 Days',
                    dateStart: function() { return moment().subtract('days', 6) },
                    dateEnd: function() { return moment()}
                }, {
                    text: 'This Month',
                    dateStart: function() { return moment().startOf('month') },
                    dateEnd: function() { return moment()}
                }, {
                    text: 'Last Month',
                    dateStart: function() { return moment().subtract('months', 1).startOf('month') },
                    dateEnd: function() { return moment().startOf('month') }
                }, {
                    text: 'Last 3 Months',
                    dateStart: function() { return moment().subtract('months', 3).startOf('month') },
                    dateEnd: function() { return moment().startOf('month') }
                }, {
                    text: 'Last 6 Months',
                    dateStart: function() { return moment().subtract('months', 6).startOf('month') },
                    dateEnd: function() { return moment().startOf('month') }
                }, {
                    text: 'This Year',
                    dateStart: function() { return moment().startOf('year') },
                    dateEnd: function() { return moment() }
                }, {
                    text: 'Last Year',
                    dateStart: function() { return moment().subtract('year', 1).startOf('year') },
                    dateEnd: function() { return moment().startOf('year') }
                }, {
                    text: 'All Time',
                    dateStart: function() { return moment("2013-01-01") },
                    dateEnd: function() { return moment().add('days', '1') }
                }],
                datepickerOptions : {
                    numberOfMonths : 2
                }
            });

            $("#date-range").daterangepicker("setRange", {
                start: moment("{{ $filters['start']->format('Y-m-d') }}").toDate(),
                end: moment("{{ $filters['end']->copy()->subDay()->format('Y-m-d') }}").toDate()
            });
        });

    </script>

@stop
