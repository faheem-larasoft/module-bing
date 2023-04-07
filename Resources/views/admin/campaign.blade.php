@extends('Admin::layout.main')
@section('meta_title', 'Campaigns Overview')
@section('content')

    <div id="Content_box">
        <div id="wide">
            <div class="margin">

                <div style="width: 100%; float:left;" class="admin">

                    <div class="quick-stats">
                        <div class="header" style="border-bottom: 0 !important;">
                            <a href="{{ route('admin_bing') }}">Bing</a> > {{ $campaign->name }}

                            <div class="input">
                                {!! Form::open(array('route' => ['admin_specific_date', 'admin-bing-dates', 'range'], 'style' => 'display:inline-block')) !!}
                                <i class="icon icon-calendar"></i>
                                <input type="text" id="date-range" name="range">
                                <input type="submit" value="Go">
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                    <br />

                    <table class="formatted" id="datatable">
                        <thead>
                        <tr class="header">
                            <td colspan="8"><h3>Adgroup</h3></td>
                            <td colspan="3"><h3>Conversion</h3></td>
                            <td colspan="4"><h3>Trades</h3></td>
                            <td colspan="3"><h3>Internal</h3></td>
                            <td colspan="2"><h3>Revenue</h3></td>
                            <td colspan="2"><h3>Monies</h3></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="campaigns_all" style="margin:0px;">
                            </td>
                            <td><h3>Name</h3></td>
                            <td><h3>Live</h3></td>
                            <td><h3>Clicks</h3></td>
                            <td><h3>Impr</h3></td>
                            <td><h3>CTR</h3></td>
                            <td><h3>CPC</h3></td>
                            <td><h3>Cost</h3></td>
                            <td><h3>Conv.</h3></td>
                            <td><h3>Quotes</h3></td>
                            <td><h3>Trades</h3></td>

                            <td><h3>Ver</h3></td>
                            <td><h3>Ver %</h3></td>
                            <td><h3>Paid Mem.</h3></td>
                            <td><h3>Has/Had</h3></td>

                            <td><h3>Priced</h3></td>
                            <td><h3>Serviced</h3></td>
                            <td><h3>Sales</h3></td>
                            <td><h3>Partner</h3></td>
                            <td><h3>Internal</h3></td>
                            <td><h3>ROI</h3></td>
                            <td><h3>Profit</h3></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($adgroups as $adgroup)
                            <tr @if($adgroup->status != 'Active') class="greyed" @endif>
                                <td>
                                    <input type="checkbox" name="adgroups[{{ $adgroup->id }}]" value="{{ $adgroup->id }}" style="margin:0px;">
                                </td>
                                <td>
                                    <a href="{{ route('admin_bing_adgroup', $adgroup->id) }}">{{ $adgroup->name }}</a>
                                </td>
                                <td data-sort="{{ $adgroup->status == 'Active' ? '0' : '1' }}">
                                    @if($adgroup->status == 'Active')
                                        <span class="icon-ok-circled greenicon"></span>
                                    @else
                                        <span class="icon-cancel-circled redicon"></span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('clicks')) }}
                                </td>
                                <td>
                                    {{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('impressions')) }}
                                </td>
                                <td>
                                    {{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('impressions') > 0 ? 100*$spend->where('adgroup_id', $adgroup->id)->sum('clicks')/$spend->where('adgroup_id', $adgroup->id)->sum('impressions') : 0, 1) }}%
                                </td>
                                <td>
                                    £{{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? $spend->where('adgroup_id', $adgroup->id)->sum('cost')/$spend->where('adgroup_id', $adgroup->id)->sum('clicks')/100 : 0, 2) }}
                                </td>
                                <td>
                                    £{{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('cost')/100, 2) }}
                                </td>
                                <td>
                                    {{ number_format($spend->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? $statistics->where('adgroup_id', $adgroup->id)->sum('quotes.total')/$spend->where('adgroup_id', $adgroup->id)->sum('clicks')*100 : 0, 1) }}%
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('quotes.total')) }}
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.total')) }}
                                </td>

                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.verified')) }}
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.total') > 0 ? $statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.verified')/$statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.total')*100 : 0, 1) }}%
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.haspaid')) }}
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('tradespeople.hashad')) }}
                                </td>

                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('quotes.priced.1')) }}
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('quotes.sold')) }}
                                </td>
                                <td>
                                    {{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('commission.internal.sales')) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('commission.external'), 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('commission.internal.cost'), 2) }}
                                </td>
                                <td @if($statistics->where('adgroup_id', $adgroup->id)->sum('commission.total.cost') > $spend->where('adgroup_id', $adgroup->id)->sum('cost')/100) class="green" @elseif($spend->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ) class="red" @endif>
                                    {{ returnOnInvestment($statistics->where('adgroup_id', $adgroup->id)->sum('commission.total.cost'), $spend->where('adgroup_id', $adgroup->id)->sum('cost')/100) }}%
                                </td>
                                <td @if($statistics->where('adgroup_id', $adgroup->id)->sum('commission.total.cost') > $spend->where('adgroup_id', $adgroup->id)->sum('cost')/100) class="green" @elseif($spend->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ) class="red" @endif>
                                    £{{ number_format($statistics->where('adgroup_id', $adgroup->id)->sum('commission.total.cost')-($spend->where('adgroup_id', $adgroup->id)->sum('cost')/100), 2) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <h3>Totals</h3>
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ number_format($spend->sum('clicks')) }}
                            </td>
                            <td>
                                {{ number_format($spend->sum('impressions')) }}
                            </td>
                            <td>
                                {{ number_format($spend->sum('impressions') > 0 ? 100*$spend->sum('clicks')/$spend->sum('impressions') : 0, 1) }}%
                            </td>
                            <td>
                                £{{ number_format($spend->sum('clicks') > 0 ? $spend->sum('cost')/$spend->sum('clicks')/100 : 0, 2) }}
                            </td>
                            <td>
                                £{{ number_format($spend->sum('cost')/100, 2) }}
                            </td>
                            <td>
                                {{ number_format($spend->sum('clicks') > 0 ? $statistics->sum('quotes.total')/$spend->sum('clicks')*100 : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($statistics->sum('quotes.total')) }}
                            </td>
                            <td>
                                {{ number_format($statistics->sum('tradespeople.total')) }}
                            </td>

                            <td>
                                {{ number_format($statistics->sum('tradespeople.verified')) }}
                            </td>
                            <td>
                                {{ number_format($statistics->sum('tradespeople.total') > 0 ? $statistics->sum('tradespeople.verified')/$statistics->sum('tradespeople.total')*100 : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($statistics->sum('tradespeople.haspaid')) }}
                            </td>
                            <td>
                                {{ number_format($statistics->sum('tradespeople.hashad')) }}
                            </td>

                            <td>
                                {{ number_format($statistics->sum('quotes.sold')) }}
                            </td>
                            <td>
                                {{ number_format($statistics->sum('commission.internal.sales')) }}
                            </td>
                            <td>
                                &pound;{{ number_format($statistics->sum('commission.external'), 2) }}
                            </td>
                            <td>
                                &pound;{{ number_format($statistics->sum('commission.external'), 2) }}
                            </td>
                            <td>
                                &pound;{{ number_format($statistics->sum('commission.internal.cost'), 2) }}
                            </td>
                            <td @if($statistics->sum('commission.total.cost') > $spend->sum('cost')/100) class="green" @elseif($spend->sum('cost') > 0 ) class="red" @endif>
                                {{ returnOnInvestment($statistics->sum('commission.total.cost'), $spend->sum('cost')/100) }}%
                            </td>
                            <td @if($statistics->sum('commission.total.cost') > $spend->sum('cost')/100) class="green" @elseif($spend->sum('cost') > 0 ) class="red" @endif>
                                £{{ number_format($statistics->sum('commission.total.cost')-($spend->sum('cost')/100), 2) }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                    <br />

                </div>
            </div>
        </div>


        @stop
        @section('javascript')
            {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.js') !!}
            <script>
                $(document).ready(function() {

                    $('#datatable').dataTable( {
                        "orderClasses": false,
                        "order": [[ 2, "asc" ], [ 1, "asc" ]],
                        "iDisplayLength": 50,
                        "bLengthChange": false,
                        "bFilter" : false,
                        "columnDefs": [
                            { "orderable": false, "targets": 0 }
                        ]
                    });

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
                            dateEnd: function() { return moment().startOf('month').subtract('days', 1) }
                        }, {
                            text: 'Last 3 Months',
                            dateStart: function() { return moment().subtract('months', 3).startOf('month') },
                            dateEnd: function() { return moment().startOf('month').subtract('days', 1) }
                        }, {
                            text: 'Last 6 Months',
                            dateStart: function() { return moment().subtract('months', 6).startOf('month') },
                            dateEnd: function() { return moment().startOf('month').subtract('days', 1) }
                        }, {
                            text: 'This Year',
                            dateStart: function() { return moment().startOf('year') },
                            dateEnd: function() { return moment() }
                        }, {
                            text: 'Last Year',
                            dateStart: function() { return moment().subtract('year', 1).startOf('year') },
                            dateEnd: function() { return moment().startOf('year').subtract('days', 1) }
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
                        start: moment("{{ $dates['start']->format('Y-m-d') }}").toDate(),
                        end: moment("{{ $dates['end']->copy()->subDay()->format('Y-m-d') }}").toDate()
                    });
                });
            </script>
@stop