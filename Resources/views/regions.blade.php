@extends('Admin::layout.main')
@section('meta_title', 'Region Adwords Data')
@section('content')

    <div id="Content_box">
        <div id="wide">
            <div class="margin">

                <div style="width: 15%; float:left;">
                    @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords', 'tab' => 'regionly'])
                </div>
                <div style="width: 2%; float:left;">&nbsp;</div>
                <div style="width: 83%; float:left;" class="admin">

                    <div class="quick-stats" style="border-bottom:0;">
                        <div class="header">
                            Regions
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
                        @foreach($campaigns->where('audience', 'homeowner')->where('status', 'ENABLED') as $campaign)
                            <option value="{{ $campaign->id }}" {{ session('admin_adwords_hourly_campaign') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }} ({{ strtolower($campaign->status) }})</option>
                        @endforeach
                    </select>
                    <input type="submit" class="button green" value="Apply">
                    {!! Form::close() !!}
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
                    <form method="post" action="{{ route('admin_adwords_regions_save') }}">
                        <table style="width:100%; " class="formatted" id="datatable">
                            <thead>
                            <tr class="header">
                                <td colspan="5"><h3>Data</h3></td>
                                <td colspan="3" class="border-left"><h3>Conversion</h3></td>
                                <td colspan="6" class="border-left"><h3>Internal</h3></td>
                                <td colspan="4" class="border-left"><h3>Partner</h3></td>
                                <td colspan="3" class="border-left"><h3>Monies</h3></td>
                            </tr>
                            <tr>
                                <td><h3>Region</h3></td>
                                <td><h3>Modifier</h3></td>
                                <td><h3>Clicks</h3></td>
                                <td><h3>CPC</h3></td>
                                <td><h3>Cost</h3></td>

                                <td><h3>Rate</h3></td>
                                <td><h3>Jobs</h3></td>
                                <td><h3>TP</h3></td>

                                <td><h3>Priced</h3></td>
                                <td><h3>Serv.</h3></td>
                                <td><h3>S.R</h3></td>
                                <td><h3>Sales</h3></td>
                                <td><h3>Rev.</h3></td>
                                <td><h3>ROI</h3></td>

                                <td><h3>Jobs</h3></td>
                                <td><h3>Sold</h3></td>
                                <td><h3>Rev.</h3></td>
                                <td><h3>ROI</h3></td>

                                <td><h3>RPQ</h3></td>
                                <td><h3>ROI</h3></td>
                                <td><h3>Profit</h3></td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($region_data as $region => $data)
                                <tr>
                                    <td data-order="{{ $region }}">
                                        {{ $regions->where('id', $region)->first()->name ?? '' }}
                                        <div style="font-size:10px;line-height:14px;">
                                            @if($modifiers->where('region_id', $region)->first())
                                                <strong>{{ $modifiers->where('region_id', $region)->first()->bid }}%</strong> as of
                                                @if($modifiers->where('region_id', $region)->first()->completed_at)
                                                    <strong>{{ \Carbon\Carbon::parse($modifiers->where('region_id', $region)->first()->completed_at)->format('jS M Y') }}</strong>
												@else
                                                    <strong>{{ \Carbon\Carbon::parse($modifiers->where('region_id', $region)->first()->created_at)->format('jS M Y') }}</strong>
                                                @endif
                                            @else
                                                -
                                            @endif
											
                                            @if($modifiers_previous->where('region_id', $region)->first())
												<br />
                                                <strong>{{ $modifiers_previous->where('region_id', $region)->first()->bid }}%</strong> as of
                                                @if($modifiers_previous->where('region_id', $region)->first()->completed_at)
                                                    <strong>{{ \Carbon\Carbon::parse($modifiers_previous->where('region_id', $region)->first()->completed_at)->format('jS M Y') }}</strong>
                                                @else
                                                    <strong>{{ \Carbon\Carbon::parse($modifiers_previous->where('region_id', $region)->first()->created_at)->format('jS M Y') }}</strong>
                                                @endif
                                            @else
                                                -
                                            @endif
											
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($modifiers->where('region_id', $region)->first() && !$modifiers->where('region_id', $region)->first()->completed_at)
                                            <div style="font-size:20px;"><i class="icon-spin3 animate-spin greyicon"></i></div>
                                        @else
                                            <input type="text" name="regions[{{ $region }}]" style="width:60px;min-width:60px;max-width:60%;">
                                        @endif
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('clicks')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($region_data->where('region_id', $region)->sum('clicks') > 0 ? $region_data->where('region_id', $region)->sum('cost')/$region_data->where('region_id', $region)->sum('clicks')/100 : 0, 2) }}
                                    </td>
                                    <td>
                                        £{{ number_format($region_data->where('region_id', $region)->sum('cost')/100, 2) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($region_data->where('region_id', $region)->sum('clicks') > 0 ? ($region_data->where('region_id', $region)->sum('quotes')+$region_data->where('region_id', $region)->sum('tradespeople'))/$region_data->where('region_id', $region)->sum('clicks')*100 : 0, 1) }}%
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('quotes')) }}
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('tradespeople')) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($region_data->where('region_id', $region)->sum('priced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('serviced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('priced') > 0 ? $region_data->where('region_id', $region)->sum('serviced')/$region_data->where('region_id', $region)->sum('priced')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        {{ number_format($region_data->where('region_id', $region)->sum('sales')) }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($region_data->where('region_id', $region)->sum('sales_amount')/100, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($region_data->where('region_id', $region)->sum('sales_amount'), $region_data->where('region_id', $region)->sum('priced_split')*$region_data->where('region_id', $region)->sum('cost')) > 0 ? 'green' : ($region_data->where('region_id', $region)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($region_data->where('region_id', $region)->sum('sales_amount'), $region_data->where('region_id', $region)->sum('priced_split')*$region_data->where('region_id', $region)->sum('cost'), 1) }}%
                                    </td>
                                    <td class="border-left">
                                        {{ $region_data->where('region_id', $region)->sum('partner') }}
                                    </td>
                                    <td>
                                        {{ $region_data->where('region_id', $region)->sum('partner_sold') }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($region_data->where('region_id', $region)->sum('partner_commission')/100, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($region_data->where('region_id', $region)->sum('partner_commission'), $region_data->where('region_id', $region)->sum('partner_split')*$region_data->where('region_id', $region)->sum('cost')) > 0 ? 'green' : ($region_data->where('region_id', $region)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($region_data->where('region_id', $region)->sum('partner_commission'), $region_data->where('region_id', $region)->sum('partner_split')*$region_data->where('region_id', $region)->sum('cost'), 1) }}%
                                    </td>
                                    <td class="border-left">
                                        &pound;{{ number_format( $region_data->where('region_id', $region)->sum('quotes') > 0 ? ($region_data->where('region_id', $region)->sum('partner_commission')+$region_data->where('region_id', $region)->sum('sales_amount'))/$region_data->where('region_id', $region)->sum('quotes')/100 : 0, 2) }}
                                    </td>
                                    <td class="border-left {{ $region_data->where('region_id', $region)->sum('partner_commission')+$region_data->where('region_id', $region)->sum('sales_amount') > $region_data->where('region_id', $region)->sum('cost') ? 'green' : ($region_data->where('region_id', $region)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($region_data->where('region_id', $region)->sum('partner_commission')+$region_data->where('region_id', $region)->sum('sales_amount'), $region_data->where('region_id', $region)->sum('cost'), 1) }}%
                                    </td>
                                    <td class="{{ $region_data->where('region_id', $region)->sum('partner_commission')+$region_data->where('region_id', $region)->sum('sales_amount') > $region_data->where('region_id', $region)->sum('cost') ? 'green' : ($region_data->where('region_id', $region)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        £{{ number_format(($region_data->where('region_id', $region)->sum('partner_commission')+$region_data->where('region_id', $region)->sum('sales_amount')-$region_data->where('region_id', $region)->sum('cost'))/100, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>

                            <tr>
                                <td>
                                    <strong>Totals</strong>
                                </td>
                                <td>

                                </td>
                                <td>
                                    {{ number_format($region_data->sum('clicks')) }}
                                </td>
                                <td>
                                    £{{ number_format($region_data->sum('clicks') > 0 ? $region_data->sum('cost')/$region_data->sum('clicks')/100 : 0, 2) }}
                                </td>
                                <td>
                                    £{{ number_format($region_data->sum('cost')/100, 2) }}
                                </td>
                                <td class="border-left">
                                    {{ number_format($region_data->sum('clicks') > 0 ? ($region_data->sum('quotes')+$region_data->sum('tradespeople'))/$region_data->sum('clicks')*100 : 0, 1) }}%
                                </td>
                                <td>
                                    {{ number_format($region_data->sum('quotes')) }}
                                </td>
                                <td>
                                    {{ number_format($region_data->sum('tradespeople')) }}
                                </td>
                                <td class="border-left">
                                    {{ number_format($region_data->sum('priced')) }}
                                </td>
                                <td>
                                    {{ number_format($region_data->sum('serviced')) }}
                                </td>
                                <td>
                                    {{ number_format($region_data->sum('priced') > 0 ? $region_data->sum('serviced')/$region_data->sum('priced')*100 : 0, 0) }}%
                                </td>
                                <td>
                                    {{ number_format($region_data->sum('sales')) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($region_data->sum('sales_amount')/100, 2) }}
                                </td>
                                <td class="{{ returnOnInvestment($region_data->sum('sales_amount'), $region_data->sum('priced_split')*$region_data->sum('cost')) > 0 ? 'green' : ($region_data->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                    {{ returnOnInvestment($region_data->sum('sales_amount'), $region_data->sum('priced_split')*$region_data->sum('cost'), 1) }}%
                                </td>
                                <td class="border-left">
                                    {{ $region_data->sum('partner') }}
                                </td>
                                <td>
                                    {{ $region_data->sum('partner_sold') }}
                                </td>
                                <td>
                                    &pound;{{ number_format($region_data->sum('partner_commission')/100, 2) }}
                                </td>
                                <td class="{{ returnOnInvestment($region_data->sum('partner_commission'), $region_data->sum('partner_split')*$region_data->sum('cost')) > 0 ? 'green' : ($region_data->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                    {{ returnOnInvestment($region_data->sum('partner_commission'), $region_data->sum('partner_split')*$region_data->sum('cost'), 1) }}%
                                </td>
                                <td class="border-left">
                                    &pound;{{ number_format( $region_data->sum('quotes') > 0 ? ($region_data->sum('partner_commission')+$region_data->sum('sales_amount'))/$region_data->sum('quotes')/100 : 0, 2) }}
                                </td>
                                <td class="border-left {{ $region_data->sum('partner_commission')+$region_data->sum('sales_amount') > $region_data->sum('cost') ? 'green' : ($region_data->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                    {{ returnOnInvestment($region_data->sum('partner_commission')+$region_data->sum('sales_amount'), $region_data->sum('cost'), 1) }}%
                                </td>
                                <td class="{{ $region_data->sum('partner_commission')+$region_data->sum('sales_amount') > $region_data->sum('cost') ? 'green' : ($region_data->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                    £{{ number_format(($region_data->sum('partner_commission')+$region_data->sum('sales_amount')-$region_data->sum('cost'))/100, 2) }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                        <button class="mjq-general-btn-lg" style="font-weight: normal;">Apply</button>
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
        });
    </script>
@stop
