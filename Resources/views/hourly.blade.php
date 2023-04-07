@extends('Admin::layout.main')
@section('meta_title', 'Hourly Adwords Data')
@section('content')

<div id="Content_box">
    <div id="wide">
        <div class="margin">

        <div style="width: 15%; float:left;">
            @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords', 'tab' => 'hourly'])
        </div>
        <div style="width: 2%; float:left;">&nbsp;</div>
        <div style="width: 83%; float:left;" class="admin">

            <div class="quick-stats" style="border-bottom:0;">
                <div class="header">
                    Hourly
                    <div class="input">
                        {!! Form::open(array('route' => ['admin_specific_date', 'admin-adwords-dates', 'range'], 'style' => 'display:inline-block')) !!}
                        @csrf
                        <i class="icon icon-calendar"></i>
                        <input type="text" id="date-range" name="range">
                        <input type="submit" value="Go">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <br />

            {!! Form::open(array('route' => array('admin_adwords_hourly'))) !!}
                <select name="day">
                    <option value="0">Any Day</option>
                    <option value="2" @if(session('admin_adwords_hourly_day') == 2) selected @endif>Monday</option>
                    <option value="3" @if(session('admin_adwords_hourly_day') == 3) selected @endif>Tuesday</option>
                    <option value="4" @if(session('admin_adwords_hourly_day') == 4) selected @endif>Wednesday</option>
                    <option value="5" @if(session('admin_adwords_hourly_day') == 5) selected @endif>Thursday</option>
                    <option value="6" @if(session('admin_adwords_hourly_day') == 6) selected @endif>Friday</option>
                    <option value="7" @if(session('admin_adwords_hourly_day') == 7) selected @endif>Saturday</option>
                    <option value="1" @if(session('admin_adwords_hourly_day') == 1) selected @endif>Sunday</option>
                </select>

                <select name="campaign">
                    <option value="0" @if(!Session::has('admin_adwords_hourly_campaign') || !Session::get('admin_adwords_hourly_campaign')) selected @endif>All</option>
                    <optgroup label="Homeowners">
                        @foreach($campaigns->where('audience', 'homeowner') as $campaign)
                            <option value="{{ $campaign->id }}" {{ session('admin_adwords_hourly_campaign') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }} ({{ strtolower($campaign->status) }})</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Tradespeople">
                        @foreach($campaigns->where('audience', 'tradesperson') as $campaign)
                            <option value="{{ $campaign->id }}" {{ session('admin_adwords_hourly_campaign') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }} ({{ strtolower($campaign->status) }})</option>
                        @endforeach
                    </optgroup>
                </select>
                <input type="submit" class="button green" value="Apply">
            {!! Form::close() !!}
            <br />

            <div style="display: block;;height:260px;border: 1px solid #ddd;padding:10px 10px 0 5px;">
                <canvas id="spend_data" height="85" width="500"></canvas>
            </div>

            <style>
                table th, table td { white-space: nowrap; }
                .admin td {
                    border-left:0;
                    border-right:0;
                    padding:5px 10px;
                }

                .admin tr.even td {background: #f1f1f1}
                .admin table tr.borders td { border-left-width: 1px; border-right-width: 1px; border-style:solid; border-color: #ddd}
                .admin table td.border-left { border-left-width: 1px; border-style:solid; border-color: #ddd}
            </style>
            <form method="post" action="{{ route('admin_adwords') }}">
                <table style="width:100%; " class="formatted" id="datatable">
                    <thead>
                    <tr class="header">
                        <td colspan="7"><h3>Data</h3></td>
                        <td colspan="3" class="border-left"><h3>Conversion</h3></td>
                        <td colspan="4" class="border-left"><h3>Trades</h3></td>
                        <td colspan="6" class="border-left"><h3>Internal</h3></td>
                        <td colspan="5" class="border-left"><h3>Partner</h3></td>
                        <td colspan="2" class="border-left"><h3>Monies</h3></td>
                    </tr>
                    <tr>
                        <td><h3>Hour</h3></td>
                        <td><h3>Clicks</h3></td>
                        <td><h3>Imp.</h3></td>
                        <td><h3>CTR</h3></td>
                        <td><h3>CPC</h3></td>
                        <td><h3>Cost</h3></td>
                        <td><h3>Pos.</h3></td>

                        <td><h3>Rate</h3></td>
                        <td><h3>Jobs</h3></td>
                        <td><h3>TP</h3></td>

                        <td><h3>Ver.</h3></td>
                        <td><h3>Ver %</h3></td>
                        <td><h3>Paid Mem.</h3></td>
                        <td><h3>Has/Had</h3></td>

                        <td><h3>Priced</h3></td>
                        <td><h3>Serv.</h3></td>
                        <td><h3>S.R</h3></td>
                        <td><h3>Sales</h3></td>
                        <td><h3>Rev.</h3></td>
                        <td><h3>ROI</h3></td>

                        <td><h3>Jobs</h3></td>
                        <td><h3>Sold</h3></td>
                        <td><h3>S.R</h3></td>
                        <td><h3>Rev.</h3></td>
                        <td><h3>ROI</h3></td>

                        <td><h3>ROI</h3></td>
                        <td><h3>Profit</h3></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(range(0,23) as $hour)
                        <tr>
                            <td data-order="{{ $hour }}">
                                {{ \Carbon\Carbon::now()->startOfDay()->addHours($hour)->format('ga') }} - {{ \Carbon\Carbon::now()->startOfDay()->addHours($hour+1)->format('ga') }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('clicks')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('impressions')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('impressions') > 0 ? 100*$hourly_data->where('hour', $hour)->sum('clicks')/$hourly_data->where('hour', $hour)->sum('impressions') : 0, 1) }}%
                            </td>
                            <td>
                                £{{ number_format($hourly_data->where('hour', $hour)->sum('clicks') > 0 ? $hourly_data->where('hour', $hour)->sum('cost')/$hourly_data->where('hour', $hour)->sum('clicks')/100 : 0, 2) }}
                            </td>
                            <td>
                                £{{ number_format($hourly_data->where('hour', $hour)->sum('cost')/100, 2) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->avg('avg_position'), 1) }}
                            </td>
                            <td class="border-left">
                                {{ number_format($hourly_data->where('hour', $hour)->sum('clicks') > 0 ? ($hourly_data->where('hour', $hour)->sum('quotes')+$hourly_data->where('hour', $hour)->sum('tradespeople'))/$hourly_data->where('hour', $hour)->sum('clicks')*100 : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('quotes')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('tradespeople')) }}
                            </td>
                            <td class="border-left">
                                {{ number_format($hourly_data->where('hour', $hour)->sum('verified')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('tradespeople') > 0 ? 100*$hourly_data->where('hour', $hour)->sum('verified')/$hourly_data->where('hour', $hour)->sum('tradespeople') : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('active_memberships')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('had_memberships')) }}
                            </td>
                            <td class="border-left">
                                {{ number_format($hourly_data->where('hour', $hour)->sum('priced')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('serviced')) }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('priced') > 0 ? $hourly_data->where('hour', $hour)->sum('serviced')/$hourly_data->where('hour', $hour)->sum('priced')*100 : 0, 0) }}%
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('sales')) }}
                            </td>
                            <td>
                                &pound;{{ number_format($hourly_data->where('hour', $hour)->sum('sales_amount')/100, 2) }}
                            </td>
                            <td class="{{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('sales_amount'), $hourly_data->where('hour', $hour)->sum('priced_split')*$hourly_data->where('hour', $hour)->sum('cost')) > 0 ? 'green' : ($hourly_data->where('hour', $hour)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                {{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('sales_amount'), $hourly_data->where('hour', $hour)->sum('priced_split')*$hourly_data->where('hour', $hour)->sum('cost'), 1) }}%
                            </td>
                            <td class="border-left">
                                {{ $hourly_data->where('hour', $hour)->sum('partner') }}
                            </td>
                            <td>
                                {{ $hourly_data->where('hour', $hour)->sum('partner_sold') }}
                            </td>
                            <td>
                                {{ number_format($hourly_data->where('hour', $hour)->sum('partner') > 0 ? $hourly_data->where('hour', $hour)->sum('partner_sold')/$hourly_data->where('hour', $hour)->sum('partner')*100 : 0, 0) }}%
                            </td>
                            <td>
                                &pound;{{ number_format($hourly_data->where('hour', $hour)->sum('partner_commission')/100, 2) }}
                            </td>
                            <td class="{{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('partner_commission'), $hourly_data->where('hour', $hour)->sum('partner_split')*$hourly_data->where('hour', $hour)->sum('cost')) > 0 ? 'green' : ($hourly_data->where('hour', $hour)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                {{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('partner_commission'), $hourly_data->where('hour', $hour)->sum('partner_split')*$hourly_data->where('hour', $hour)->sum('cost'), 1) }}%
                            </td>
                            <td class="border-left {{ $hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount') > $hourly_data->where('hour', $hour)->sum('cost') ? 'green' : ($hourly_data->where('hour', $hour)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                {{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount'), $hourly_data->where('hour', $hour)->sum('cost'), 1) }}%
                            </td>
                            <td class="{{ $hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount') > $hourly_data->where('hour', $hour)->sum('cost') ? 'green' : ($hourly_data->where('hour', $hour)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                £{{ number_format(($hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount')-$hourly_data->where('hour', $hour)->sum('cost'))/100, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </form>
                
        </div>
    </div>
</div>
@stop

@section('javascript')
{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.js') !!}
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

    function numberWithCommas(x, dp) {
        x = x.toFixed(dp);
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function returnOnInvestment(revenue, cost)
    {
        return cost > 0 ? (revenue-cost)/cost*100 : 0;
    }

    var table = $('#datatable').DataTable({
        "orderClasses": false,
        "order": [[0, "asc"]],
        "iDisplayLength": 25,
        "bLengthChange": false,
        "sScrollX": "100%",
        "bFilter": true,
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\£%$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            clicks = api.column(1, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            $(api.column(1).footer()).html(
                numberWithCommas(clicks, 0)
            );

            impressions = api.column(2, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            cost = api.column(5, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            $(api.column(2).footer()).html(
                numberWithCommas(impressions, 0)
            );

            $(api.column(3).footer()).html(
                numberWithCommas(impressions > 0 ? clicks / impressions * 100 : 0, 1) + '%'
            );

            $(api.column(4).footer()).html(
                '&pound;' + numberWithCommas(clicks > 0 ? cost / clicks : 0, 2)
            );

            $(api.column(5).footer()).html(
                '&pound;' + numberWithCommas(cost, 2)
            );

            quotes = api.column(8, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            trades = api.column(9, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            $(api.column(7).footer()).html(
                numberWithCommas(clicks > 0 ? (quotes + trades) / clicks * 100 : 0, 1) + '%'
            );

            $(api.column(8).footer()).html(
                numberWithCommas(quotes, 0)
            );

            $(api.column(9).footer()).html(
                numberWithCommas(trades, 0)
            );

            priced = api.column(10, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            serviced = api.column(11, {search: 'applied'}).data().reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            $(api.column(10).footer()).html(
                numberWithCommas(priced, 0)
            );

            $(api.column(11).footer()).html(
                numberWithCommas(serviced, 0)
            );


            verified = api.column( 12 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 12 ).footer() ).html(
                numberWithCommas(verified, 0)
            );

            $( api.column( 13 ).footer() ).html(
                numberWithCommas(trades > 0 ? (verified)/trades*100 : 0, 1)+'%'
            );

            paid = api.column( 14 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 14 ).footer() ).html(
                numberWithCommas(paid, 0)
            );

            hashad = api.column( 15 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 15 ).footer() ).html(
                numberWithCommas(hashad, 0)
            );


            priced = api.column( 16 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            serviced = api.column( 17 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 16 ).footer() ).html(
                numberWithCommas(priced, 0)
            );

            $( api.column( 17 ).footer() ).html(
                numberWithCommas(serviced, 0)
            );

            sales = api.column( 19 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            revenue = api.column( 20 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 18 ).footer() ).html(
                numberWithCommas(priced > 0 ? serviced/priced*100 : 0, 1)+'%'
            );

            $( api.column( 19 ).footer() ).html(
                numberWithCommas(sales, 0)
            );

            $( api.column( 20 ).footer() ).html(
                '£'+numberWithCommas(revenue, 2)
            );

            internal_percent = quotes > 0 ? priced/quotes : 0;
            internal_cost = internal_percent*cost;

            $( api.column( 21 ).footer() ).html(
                numberWithCommas(returnOnInvestment(revenue, internal_cost), 1)+'%'
            );

            partner_count = api.column( 22 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 22 ).footer() ).html(
                numberWithCommas(partner_count)
            );

            partner_sold = api.column( 23 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 23 ).footer() ).html(
                numberWithCommas(partner_sold)
            );

            $( api.column( 24 ).footer() ).html(
                numberWithCommas(partner_count > 0 ? partner_sold/partner_count*100 : 0)+'%'
            );

            partner = api.column( 25 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 25 ).footer() ).html(
                '£'+numberWithCommas(partner, 2)
            );

            partner_count = quotes-priced;
            partner_percent = quotes > 0 ? partner_count/quotes : 0;
            partner_cost = partner_percent*cost;
            $( api.column( 26 ).footer() ).html(
                numberWithCommas(returnOnInvestment( partner, partner_cost), 1)+'%'
            );

            profit = api.column( 28 , {search: 'applied'}).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 27 ).footer() ).html(
                numberWithCommas(returnOnInvestment(revenue+partner, cost), 1)+'%'
            );

            $( api.column( 28 ).footer() ).html(
                '£'+numberWithCommas(profit, 2)
            );
        }
    });

    window.chartOptions = {
        segmentShowStroke: false,
        percentageInnerCutout: 75,
        animation: false
    };

    var ctx = document.getElementById("spend_data");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [@foreach(range(0,23) as $hour) "{{ \Carbon\Carbon::now()->startOfDay()->addHours($hour)->format('ga') }}", @endforeach],
            datasets: [
                {
                    label: "Spend",
                    fill: false,
                    borderColor: "rgba(255,37,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 2,
                    pointHitRadius: 15,
                    yAxisID: "y-axis-1",
                    data: [@foreach(range(0,23) as $hour) "{{ number_format($hourly_data->where('hour', $hour)->sum('cost')/100, 2) }}", @endforeach]
                },
                {
                    label: "Profit",
                    fill: false,
                    borderColor: "rgba(0,10,200,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 2,
                    pointHitRadius: 15,
                    yAxisID: "y-axis-1",
                    data: [@foreach(range(0,23) as $hour) "{{ number_format(($hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount')-$hourly_data->where('hour', $hour)->sum('cost'))/100, 2) }}", @endforeach]
                },
                {
                    label: "ROI",
                    fill: false,
                    borderColor: "lightgrey",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 2,
                    pointHitRadius: 15,
                    yAxisID: "y-axis-2",
                    data: [@foreach(range(0,23) as $hour) "{{ returnOnInvestment($hourly_data->where('hour', $hour)->sum('partner_commission')+$hourly_data->where('hour', $hour)->sum('sales_amount'), $hourly_data->where('hour', $hour)->sum('cost'), 1)  }}", @endforeach]
                }
            ]
        },
        options: {
            tooltips: {
                mode: 'label',
            },
            hover: {
                mode: 'label'
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Month'
                    },
                    ticks: {
                        fontSize: 10
                    }
                }],
                yAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    ticks: {
                        beginAtZero: true
                    },
                    position: "left",
                    id: "y-axis-1",
                }, {
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "right",
                    ticks: {
                        beginAtZero: true
                    },
                    id: "y-axis-2",
                    // grid line settings
                    gridLines: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    }
                }]
            }
        }
    });
});
</script>
@stop