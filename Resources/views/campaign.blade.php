@extends('Admin::layout.main')
@section('meta_title', $campaign->name .' Adgroups')
@section('content')

    <style>
        .quick-stats .stats-box .general-sixth {
            line-height: 40px;
            text-align: center;
            padding-left:.5%;
            width:15.5%;
            padding-right:.5%;
            border-right: 1px solid #ddd;
            float:left;
        }

        .quick-stats .general-sixth.last {
            border:0;
        }

        #datatable thead td h3 {
            font-size:12px !important;
        }
    </style>

<div id="Content_box">
    <div id="wide">
        <div class="margin">

            <div style="position:fixed;left:0;top:85px;width:40px;height:100px;  z-index:20001; cursor: pointer; color:white; background-color: #2875d7;" class="show-sidebar"><div style="margin-top:30px;transform: rotate(-90deg);">nav</div></div>
            <div style="display:none;width: 15%; float:left;" class="lhs-nav">
                @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords'])
            </div>
            <div style="width: 2%; float:left;display:none;" class="lrhs-seperator">&nbsp;</div>
            <div data-width="83%" data-wide="100%" style="width: 100%; float:left;" class="admin rhs-page">

            <div class="quick-stats">
                <div class="header" style="border-bottom:0;">
                    <a href="{{ URL::route('admin_adwords') }}">Campaigns</a> >
                    {{ $campaign->name }}

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
                        <td colspan="5"><h3>Adgroup</h3></td>
                        <td colspan="5" class="border-left"><h3>Conversion</h3></td>
                        <td colspan="3" class="border-left"><h3>Trades</h3></td>
                        <td colspan="7" class="border-left"><h3>Sales</h3></td>
                        <!--
                        <td colspan="5" class="border-left"><h3>Partner</h3></td>

                        -->
                        <td colspan="4" class="border-left"><h3>Monies</h3></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="campaigns_all" style="margin:0px;">
                        </td>
                        <td><h3>Name</h3></td>
                        <td><h3>Clicks</h3></td>
                        <td><h3>CPC</h3></td>
                        <td><h3>Cost</h3></td>

                        <td><h3>Rate</h3></td>
                        <td><h3>Jobs</h3></td>
                        <td><h3>CPQ</h3></td>
                        <td><h3>TP</h3></td>
                        <td><h3>CPT</h3></td>

                        <td><h3>Ver.</h3></td>
                        <td><h3>Ver %</h3></td>
                        <td><h3>CPV</h3></td>
                        <!--
                        <td><h3>Paid Mem.</h3></td>
                        <td><h3>Has/Had</h3></td>
                        --->

                        <td><h3>Priced</h3></td>
                        <td><h3>Serv.</h3></td>
                        <td><h3>S.R</h3></td>
                        <td><h3>Sales</h3></td>
                        <td><h3>Rev.</h3></td>
                        <td><h3>RPS</h3></td>
                        <td><h3>ROI</h3></td>

                        <!--
                        <td><h3>Jobs</h3></td>
                        <td><h3>Sold</h3></td>
                        <td><h3>S.R</h3></td>
                        <td><h3>Rev.</h3></td>
                        <td><h3>ROI</h3></td>
                        -->

                        <td><h3>RPQ</h3></td>
                        <td><h3>ROI</h3></td>
                        <td><h3>Profit</h3></td>
                        <td><h3>IR</h3></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($adgroups as $adgroup)
                        <tr @if($adgroup->status != 'ENABLED') style="color:grey;" @endif>
                            <td>
                                <input type="checkbox" name="campaigns[{{ $adgroup->id }}]" value="{{ $adgroup->id }}" style="margin:0px;">
                            </td>
                            <td>
                                <a href="{{ route('admin_adwords_adgroup', $adgroup->id) }}" @if($adgroup->status != 'ENABLED') style="color:grey;" @endif >{{ \Illuminate\Support\Str::limit($adgroup->name, 30, '...') }}</a>
                            </td>
                            <td data-filter="{{ $campaign->status == 'ENABLED' ? 'ENABLED' : 'PAUSED' }}">
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks')) }}
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('cost')/$data->where('adgroup_id', $adgroup->id)->sum('clicks')/100 : 0, 2) }}
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('cost')/100, 2) }}
                            </td>
                            <td class="border-left">
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? ($data->where('adgroup_id', $adgroup->id)->sum('quotes')+$data->where('adgroup_id', $adgroup->id)->sum('tradespeople'))/$data->where('adgroup_id', $adgroup->id)->sum('clicks')*100 : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('quotes')) }}
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('quotes') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('cost')/$data->where('adgroup_id', $adgroup->id)->sum('quotes')/100 : 0, 2) }}
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('tradespeople')) }}
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('tradespeople') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('cost')/$data->where('adgroup_id', $adgroup->id)->sum('tradespeople')/100 : 0, 2) }}
                            </td>
                            <td class="border-left">
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('verified')) }}
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('tradespeople') > 0 ? 100*$data->where('adgroup_id', $adgroup->id)->sum('verified')/$data->where('adgroup_id', $adgroup->id)->sum('tradespeople') : 0, 1) }}%
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('verified') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('cost')/$data->where('adgroup_id', $adgroup->id)->sum('verified')/100 : 0, 2) }}
                            </td>
                            <!---
                                    <td>
                                        {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('active_memberships')) }}
                            </td>
                            <td>
{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('had_memberships')) }}
                            </td>
                            ---->
                            <td class="border-left">
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('priced')) }}
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('serviced')) }}
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('priced') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('serviced')/$data->where('adgroup_id', $adgroup->id)->sum('priced')*100 : 0, 0) }}%
                            </td>
                            <td>
                                {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('sales')) }}
                            </td>
                            <td>
                                &pound;{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('sales_amount')/100, 2) }}
                            </td>
                            <td>
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('serviced') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('sales_amount')/$data->where('adgroup_id', $adgroup->id)->sum('serviced')/100 : 0, 2) }}
                            </td>
                            <td class="{{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('sales_amount'), $data->where('adgroup_id', $adgroup->id)->sum('priced_split')*$data->where('adgroup_id', $adgroup->id)->sum('cost')) > 0 ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                {{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('sales_amount'), $data->where('adgroup_id', $adgroup->id)->sum('priced_split')*$data->where('adgroup_id', $adgroup->id)->sum('cost'), 1) }}%
                            </td>
                            <!---
                                    <td class="border-left">
                                        {{ $data->where('adgroup_id', $adgroup->id)->sum('partner') }}
                            </td>
                            <td>
{{ $data->where('adgroup_id', $adgroup->id)->sum('partner_sold') }}
                            </td>
                            <td>
{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('partner') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('partner_sold')/$data->where('adgroup_id', $adgroup->id)->sum('partner')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        &pound;{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')/100, 2) }}
                            </td>
                            <td class="{{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('partner_commission'), $data->where('adgroup_id', $adgroup->id)->sum('partner_split')*$data->where('adgroup_id', $adgroup->id)->sum('cost')) > 0 ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('partner_commission'), $data->where('adgroup_id', $adgroup->id)->sum('partner_split')*$data->where('adgroup_id', $adgroup->id)->sum('cost'), 1) }}%
                                    </td>
                                    --->

                            <td class="border-left">
                                £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('quotes') > 0 ? ($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount'))/$data->where('adgroup_id', $adgroup->id)->sum('quotes')/100 : 0, 2) }}
                            </td>
                            <td class="border-left {{ $data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount') > $data->where('adgroup_id', $adgroup->id)->sum('cost') ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                {{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount'), $data->where('adgroup_id', $adgroup->id)->sum('cost'), 1) }}%
                            </td>
                            <td class="{{ $data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount') > $data->where('adgroup_id', $adgroup->id)->sum('cost') ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                £{{ number_format(($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount')-$data->where('adgroup_id', $adgroup->id)->sum('cost'))/100, 2) }}
                            </td>
                            <td>
                                &pound;{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('instant_revenue_amount')/100, 2) }}
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
                    </tr>
                    </tfoot>
                </table>

                <select name="action">
                    <option value="0"> - With Selected -</option>
                    <option value="enable">Enable</option>
                    <option value="pause">Pause</option>
                </select>
                <input type="submit" class="button green" value="Change Status">
            </form>

        </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            function numberWithCommas(x, dp) {
                x = x.toFixed(dp);
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            function returnOnInvestment(revenue, cost)
            {
                return cost > 0 ? (revenue-cost)/cost*100 : 0;
            }

            $("#date-range").daterangepicker({
                presetRanges: [{
                    text: 'Today',
                    dateStart: function () {
                        return moment()
                    },
                    dateEnd: function () {
                        return moment()
                    }
                }, {
                    text: 'Yesterday',
                    dateStart: function () {
                        return moment().subtract('days', 1)
                    },
                    dateEnd: function () {
                        return moment().subtract('days', 1)
                    }
                }, {
                    text: 'Last 7 Days',
                    dateStart: function () {
                        return moment().subtract('days', 6)
                    },
                    dateEnd: function () {
                        return moment()
                    }
                }, {
                    text: 'This Month',
                    dateStart: function () {
                        return moment().startOf('month')
                    },
                    dateEnd: function () {
                        return moment()
                    }
                }, {
                    text: 'Last Month',
                    dateStart: function () {
                        return moment().subtract('months', 1).startOf('month')
                    },
                    dateEnd: function () {
                        return moment().startOf('month').subtract('days', 1)
                    }
                }, {
                    text: 'Last 3 Months',
                    dateStart: function () {
                        return moment().subtract('months', 3).startOf('month')
                    },
                    dateEnd: function () {
                        return moment().startOf('month').subtract('days', 1)
                    }
                }, {
                    text: 'Last 6 Months',
                    dateStart: function () {
                        return moment().subtract('months', 6).startOf('month')
                    },
                    dateEnd: function () {
                        return moment().startOf('month').subtract('days', 1)
                    }
                }, {
                    text: 'This Year',
                    dateStart: function () {
                        return moment().startOf('year')
                    },
                    dateEnd: function () {
                        return moment()
                    }
                }, {
                    text: 'Last Year',
                    dateStart: function () {
                        return moment().subtract('year', 1).startOf('year')
                    },
                    dateEnd: function () {
                        return moment().startOf('year').subtract('days', 1)
                    }
                }, {
                    text: 'All Time',
                    dateStart: function () {
                        return moment("2013-01-01")
                    },
                    dateEnd: function () {
                        return moment().add('days', '1')
                    }
                }],
                datepickerOptions: {
                    numberOfMonths: 2
                }
            });

            $("#date-range").daterangepicker("setRange", {
                start: moment("{{ $dates['start']->format('Y-m-d') }}").toDate(),
                end: moment("{{ $dates['end']->copy()->subDay()->format('Y-m-d') }}").toDate()
            });

            var table = $('#datatable').DataTable({
                "orderClasses": false,
                "order": [[4, "desc"]],
                "iDisplayLength": 25,
                "bLengthChange": false,
                "sScrollX": "100%",
                "bFilter": true,
                "columnDefs": [
                    {"orderable": false, "targets": 0}
                ],
                "initComplete": function (settings, json) {
                    this.api (). columns (). header (). each ((el, i) => {
                        $ (el) .attr ('style', 'min-width: 30px;')
                    });
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    this.api (). columns (). header (). each ((el, i) => {
                        $ (el) .attr ('style', 'min-width: 30px;')
                    });

                    var api = this.api(), data;

                    var intVal = function ( i ) {
                        return typeof i === 'string' ? i.replace(/[\£%$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                    };

                    clicks = api.column( 2 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 2 ).footer() ).html(
                        numberWithCommas(clicks, 0)
                    );


                    cost = api.column( 4 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 3 ).footer() ).html(
                        '&pound;'+numberWithCommas(clicks > 0 ? cost/clicks : 0, 2)
                    );

                    $( api.column( 4 ).footer() ).html(
                        '&pound;'+numberWithCommas(cost, 2)
                    );

                    quotes = api.column( 6 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    trades = api.column( 8 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 5 ).footer() ).html(
                        numberWithCommas(clicks > 0 ? (quotes+trades)/clicks*100 : 0, 1)+'%'
                    );

                    $( api.column( 6 ).footer() ).html(
                        numberWithCommas(quotes, 0)
                    );

                    $( api.column( 7 ).footer() ).html(
                        '&pound;'+numberWithCommas(quotes > 0 ? (cost)/quotes : 0, 2)
                    );

                    $( api.column( 8 ).footer() ).html(
                        numberWithCommas(trades, 0)
                    );

                    $( api.column( 9 ).footer() ).html(
                        '&pound;'+numberWithCommas(trades > 0 ? (cost)/trades : 0, 2)
                    );

                    verified = api.column( 10 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 10 ).footer() ).html(
                        numberWithCommas(verified, 0)
                    );

                    $( api.column( 11 ).footer() ).html(
                        numberWithCommas(trades > 0 ? (verified)/trades*100 : 0, 1)+'%'
                    );

                    $( api.column( 12 ).footer() ).html(
                        '&pound;'+numberWithCommas(cost > 0 ? (cost)/verified : 0, 2)
                    );

                    /*
                    paid = api.column( 11 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 11 ).footer() ).html(
                        numberWithCommas(paid, 0)
                    );

                    hashad = api.column( 12 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 12 ).footer() ).html(
                        numberWithCommas(hashad, 0)
                    );*/

                    priced = api.column( 13 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    serviced = api.column( 14 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 13 ).footer() ).html(
                        numberWithCommas(priced, 0)
                    );

                    $( api.column( 14 ).footer() ).html(
                        numberWithCommas(serviced, 0)
                    );

                    sales = api.column( 16 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    revenue = api.column( 17 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 15 ).footer() ).html(
                        numberWithCommas(priced > 0 ? serviced/priced*100 : 0, 1)+'%'
                    );

                    $( api.column( 16 ).footer() ).html(
                        numberWithCommas(sales, 0)
                    );

                    $( api.column( 17 ).footer() ).html(
                        '£'+numberWithCommas(revenue, 2)
                    );

                    $( api.column( 18 ).footer() ).html(
                        '&pound;'+numberWithCommas(serviced > 0 ? revenue/serviced : 0, 2)
                    );

                    internal_percent = quotes > 0 ? priced/quotes : 0;
                    internal_cost = internal_percent*cost;
                    $( api.column( 19 ).footer() ).html(
                        numberWithCommas(returnOnInvestment(revenue, internal_cost), 1)+'%'
                    );

                    /*
                    partner_count = api.column( 18 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 18 ).footer() ).html(
                        numberWithCommas(partner_count)
                    );

                    partner_sold = api.column( 19 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 19 ).footer() ).html(
                        numberWithCommas(partner_sold)
                    );

                    $( api.column( 20 ).footer() ).html(
                        numberWithCommas(partner_count > 0 ? partner_sold/partner_count*100 : 0)+'%'
                    );

                    partner = api.column( 21 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 21 ).footer() ).html(
                        '£'+numberWithCommas(partner, 2)
                    );

                    partner_count = quotes-priced;
                    partner_percent = quotes > 0 ? partner_count/quotes : 0;
                    partner_cost = partner_percent*cost;

                    $( api.column( 22 ).footer() ).html(
                        numberWithCommas(returnOnInvestment(partner, partner_cost), 1)+'%'
                    );*/

                    $( api.column( 20 ).footer() ).html(
                        '&pound;'+numberWithCommas(quotes > 0 ? (revenue)/quotes : 0, 2)
                    );

                    profit = api.column( 22 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 21 ).footer() ).html(
                        numberWithCommas(returnOnInvestment(revenue, cost), 1)+'%'
                    );

                    $( api.column( 22 ).footer() ).html(
                        '£'+numberWithCommas(profit, 2)
                    );


                    ir = api.column( 23 , {search: 'applied'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    $( api.column( 23 ).footer() ).html(
                        '£'+numberWithCommas(ir, 2)
                    );

                }
            });

        });
    </script>
@stop
