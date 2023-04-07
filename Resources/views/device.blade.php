@extends('Admin::layout.main')
@section('meta_title', 'Devices Adwords Data')
@section('content')

<div id="Content_box">
    <div id="wide">
        <div class="margin">

        <div style="width: 15%; float:left;">
            @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords', 'tab' => 'devices'])
        </div>
        <div style="width: 2%; float:left;">&nbsp;</div>
        <div style="width: 83%; float:left;" class="admin">

            <div class="quick-stats" style="border-bottom:0;">
                <div class="header">
                    Device Data,

                    @if(isset($dates['display']) AND $dates['display'])
                        <strong>{{ $dates['display'] }}</strong> {{ $dates['start']->format('jS M Y') }}
                    @else
                        <strong>Custom</strong> {{ $dates['start']->format('jS M Y') }} - {{ $dates['end']->copy()->subDay()->format('jS M Y') }}
                    @endif

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
                <select name="campaign">
                    <option value="0" @if(!Session::has('admin_adwords_hourly_campaign') || !Session::get('admin_adwords_hourly_campaign')) selected @endif>All</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ session('admin_adwords_hourly_campaign') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                    @endforeach
                </select>
                <input type="submit" class="button green" value="Apply">
            {!! Form::close() !!}

            <br />

            <table class="formatted" id="datatable" style="
    display: block;
    overflow-x: auto;
    white-space: nowrap;">
                <thead>
                <tr class="header">
                    <td colspan="7"><h3>Adwords</h3></td>
                    <td colspan="3"><h3>Conversion</h3></td>
                    <td colspan="4"><h3>Trades</h3></td>
                    <td colspan="3"><h3>Internal</h3></td>
                    <td colspan="2"><h3>Revenue</h3></td>
                    <td colspan="2"><h3>Monies</h3></td>
                </tr>
                <tr>
                    <td><h3>Ad</h3></td>
                    <td><h3>Clicks</h3></td>
                    <td><h3>Impr</h3></td>
                    <td><h3>CTR</h3></td>
                    <td><h3>CPC</h3></td>
                    <td><h3>Cost</h3></td>
                    <td><h3>Pos</h3></td>
                    <td><h3>Conv.</h3></td>
                    <td><h3>Quotes</h3></td>
                    <td><h3>Trades</h3></td>

                    <td><h3>Ver.</h3></td>
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
                <tfoot>
                <tr>
                    <td><h3>Totals</h3></td>
                    <td>
                        {{ number_format($device_data->sum('clicks')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('impressions')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('impressions') > 0 ? 100*$device_data->sum('clicks')/$device_data->sum('impressions') : 0, 1) }}%
                    </td>
                    <td>
                        £{{ number_format($device_data->sum('clicks') > 0 ? $device_data->sum('cost')/$device_data->sum('clicks')/100 : 0, 2) }}
                    </td>
                    <td>
                        £{{ number_format($device_data->sum('cost')/100, 2) }}
                    </td>
                    <td>
                        {{ number_format($device_data->avg('avg_position'), 1) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('clicks') > 0 ? ($device_data->sum('quotes')+$device_data->sum('tradespeople'))/$device_data->sum('clicks')*100 : 0, 1) }}%
                    </td>
                    <td>
                        {{ number_format($device_data->sum('quotes')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('tradespeople')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('verified')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('tradespeople') > 0 ? 100*$device_data->sum('verified')/$device_data->sum('tradespeople') : 0, 1) }}%
                    </td>
                    <td>
                        {{ number_format($device_data->sum('active_memberships')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('had_memberships')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('priced')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('serviced')) }}
                    </td>
                    <td>
                        {{ number_format($device_data->sum('sales')) }}
                    </td>
                    <td>
                        &pound;{{ number_format($device_data->sum('partner_commission')/100, 2) }}
                    </td>
                    <td>
                        &pound;{{ number_format($device_data->sum('sales_amount')/100, 2) }}
                    </td>
                    <td class="{{ $device_data->sum('partner_commission')+$device_data->sum('sales_amount') > $device_data->sum('cost') ? 'green' : ($device_data->sum('cost') > 0 ? 'red' : '') }}">
                        {{ returnOnInvestment($device_data->sum('partner_commission')+$device_data->sum('sales_amount'), $device_data->sum('cost')) }}%
                    </td>
                    <td class="{{ $device_data->sum('partner_commission')+$device_data->sum('sales_amount') > $device_data->sum('cost') ? 'green' : ($device_data->sum('cost') > 0 ? 'red' : '') }}">
                        £{{ number_format(($device_data->sum('partner_commission')+$device_data->sum('sales_amount')-$device_data->sum('cost'))/100, 2) }}
                    </td>
                </tr>
                </tfoot>
                <tbody>
                @foreach($device_data->groupBy('device') as $device => $data)
                    <tr>
                        <td data-order="{{ $device }}">
                            {{ $device }}
                        </td>

                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('clicks')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('impressions')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('impressions') > 0 ? 100*$device_data->where('device', $device)->sum('clicks')/$device_data->where('device', $device)->sum('impressions') : 0, 1) }}%
                        </td>
                        <td>
                            £{{ number_format($device_data->where('device', $device)->sum('clicks') > 0 ? $device_data->where('device', $device)->sum('cost')/$device_data->where('device', $device)->sum('clicks')/100 : 0, 2) }}
                        </td>
                        <td>
                            £{{ number_format($device_data->where('device', $device)->sum('cost')/100, 2) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->avg('avg_position'), 1) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('clicks') > 0 ? ($device_data->where('device', $device)->sum('quotes')+$device_data->where('device', $device)->sum('tradespeople'))/$device_data->where('device', $device)->sum('clicks')*100 : 0, 1) }}%
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('quotes')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('tradespeople')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('verified')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('tradespeople') > 0 ? 100*$device_data->where('device', $device)->sum('verified')/$device_data->where('device', $device)->sum('tradespeople') : 0, 1) }}%
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('active_memberships')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('had_memberships')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('priced')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('serviced')) }}
                        </td>
                        <td>
                            {{ number_format($device_data->where('device', $device)->sum('sales')) }}
                        </td>
                        <td>
                            &pound;{{ number_format($device_data->where('device', $device)->sum('partner_commission')/100, 2) }}
                        </td>
                        <td>
                            &pound;{{ number_format($device_data->where('device', $device)->sum('sales_amount')/100, 2) }}
                        </td>
                        <td class="{{ $device_data->where('device', $device)->sum('partner_commission')+$device_data->where('device', $device)->sum('sales_amount') > $device_data->where('device', $device)->sum('cost') ? 'green' : ($device_data->where('device', $device)->sum('cost') > 0 ? 'red' : '') }}">
                            {{ returnOnInvestment($device_data->where('device', $device)->sum('partner_commission')+$device_data->where('device', $device)->sum('sales_amount'), $device_data->where('device', $device)->sum('cost')) }}%
                        </td>
                        <td class="{{ $device_data->where('device', $device)->sum('partner_commission')+$device_data->where('device', $device)->sum('sales_amount') > $device_data->where('device', $device)->sum('cost') ? 'green' : ($device_data->where('device', $device)->sum('cost') > 0 ? 'red' : '') }}">
                            £{{ number_format(($device_data->where('device', $device)->sum('partner_commission')+$device_data->where('device', $device)->sum('sales_amount')-$device_data->where('device', $device)->sum('cost'))/100, 2) }}
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
    
    $('#datatable').dataTable( {
        "orderClasses": false,
        "order": [[ 0, "desc" ]],
        "iDisplayLength": 50,
        "bLengthChange": false,
        "bFilter" : true,
        "fnDrawCallback": function() {
            if (Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength) > 1)  {
                    $('.dataTables_paginate').css("display", "block");
                    $('.dataTables_length').css("display", "block");
            } else {
                    $('.dataTables_paginate').css("display", "none");
                    $('.dataTables_length').css("display", "none");
            }
        }
     });
} );
</script>
@stop
