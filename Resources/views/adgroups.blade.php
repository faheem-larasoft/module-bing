@extends('Admin::layout.main')
@section('meta_title', 'All Adgroups')
@section('content')

    <div id="Content_box">
        <div id="wide">
            <div class="margin">

                <div style="width: 15%; float:left;">
                    @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords', 'tab' => 'adgroups'])
                </div>
                <div style="width: 2%; float:left;">&nbsp;</div>
                <div style="width: 83%; float:left;" class="admin">

                    <div class="quick-stats" style="border-bottom: 0;">
                        <div class="header">
                            Adgroups

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


                    <table class="formatted" id="datatable">
                        <thead>
                        <tr class="header">
                            <td colspan="9"><h3>Adgroup</h3></td>
                            <td colspan="3"><h3>Conversion</h3></td>
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
                            <td><h3>Pos</h3></td>
                            <td><h3>Conv.</h3></td>
                            <td><h3>Quotes</h3></td>
                            <td><h3>Trades</h3></td>

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
                            <tr @if($adgroup->status != 'ENABLED') style="color:grey;" @endif>
                                <td>
                                    <input type="checkbox" name="campaigns[{{ $adgroup->id }}]" value="{{ $adgroup->id }}" style="margin:0px;">
                                </td>
                                <td>
                                    <a href="{{ route('admin_adwords_campaign', $adgroup->id) }}" @if($adgroup->status != 'ENABLED') style="color:grey;" @endif >{{ $adgroup->name }}</a>
                                </td>
                                <td data-sort="{{ $adgroup->status == 'ENABLED' ? '0' : '1' }}">
                                    @if($adgroup->status == 'ENABLED')
                                        <span class="icon-ok-circled greenicon"></span>
                                    @else
                                        <span class="icon-cancel-circled redicon"></span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('impressions')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('impressions') > 0 ? 100*$data->where('adgroup_id', $adgroup->id)->sum('clicks')/$data->where('adgroup_id', $adgroup->id)->sum('impressions') : 0, 1) }}%
                                </td>
                                <td>
                                    £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? $data->where('adgroup_id', $adgroup->id)->sum('cost')/$data->where('adgroup_id', $adgroup->id)->sum('clicks')/100 : 0, 2) }}
                                </td>
                                <td>
                                    £{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('cost')/100, 2) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->avg('avg_position'), 1) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('clicks') > 0 ? ($data->where('adgroup_id', $adgroup->id)->sum('quotes')+$data->where('adgroup_id', $adgroup->id)->sum('tradespeople'))/$data->where('adgroup_id', $adgroup->id)->sum('clicks')*100 : 0, 1) }}%
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('quotes')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('tradespeople')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('priced')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('serviced')) }}
                                </td>
                                <td>
                                    {{ number_format($data->where('adgroup_id', $adgroup->id)->sum('sales')) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')/100, 2) }}
                                </td>
                                <td>
                                    &pound;{{ number_format($data->where('adgroup_id', $adgroup->id)->sum('sales_amount')/100, 2) }}
                                </td>
                                <td class="{{ $data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount') > $data->where('adgroup_id', $adgroup->id)->sum('cost') ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : '') }}">
                                    {{ returnOnInvestment($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount'), $data->where('adgroup_id', $adgroup->id)->sum('cost')) }}%
                                </td>
                                <td class="{{ $data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount') > $data->where('adgroup_id', $adgroup->id)->sum('cost') ? 'green' : ($data->where('adgroup_id', $adgroup->id)->sum('cost') > 0 ? 'red' : '') }}">
                                    £{{ number_format(($data->where('adgroup_id', $adgroup->id)->sum('partner_commission')+$data->where('adgroup_id', $adgroup->id)->sum('sales_amount')-$data->where('adgroup_id', $adgroup->id)->sum('cost'))/100, 2) }}
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
                                {{ number_format($data->sum('clicks')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('impressions')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('impressions') > 0 ? 100*$data->sum('clicks')/$data->sum('impressions') : 0, 1) }}%
                            </td>
                            <td>
                                £{{ number_format($data->sum('clicks') > 0 ? $data->sum('cost')/$data->sum('clicks')/100 : 0, 2) }}
                            </td>
                            <td>
                                £{{ number_format($data->sum('cost')/100, 2) }}
                            </td>
                            <td>
                                {{ number_format($data->avg('avg_position'), 1) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('clicks') > 0 ? ($data->sum('quotes')+$data->sum('tradespeople'))/$data->sum('clicks')*100 : 0, 1) }}%
                            </td>
                            <td>
                                {{ number_format($data->sum('quotes')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('tradespeople')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('priced')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('serviced')) }}
                            </td>
                            <td>
                                {{ number_format($data->sum('sales')) }}
                            </td>
                            <td>
                                &pound;{{ number_format($data->sum('partner_commission')/100, 2) }}
                            </td>
                            <td>
                                &pound;{{ number_format($data->sum('sales_amount')/100, 2) }}
                            </td>
                            <td class="{{ $data->sum('partner_commission')+$data->sum('sales_amount') > $data->sum('cost') ? 'green' : ($data->sum('cost') > 0 ? 'red' : '') }}">
                                {{ returnOnInvestment($data->sum('partner_commission')+$data->sum('sales_amount'), $data->sum('cost')) }}%
                            </td>
                            <td class="{{ $data->sum('partner_commission')+$data->sum('sales_amount') > $data->sum('cost') ? 'green' : ($data->sum('cost') > 0 ? 'red' : '') }}">
                                £{{ number_format(($data->sum('partner_commission')+$data->sum('sales_amount')-$data->sum('cost'))/100, 2) }}
                            </td>
                        </tr>
                        </tfoot>
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
                        "order": [[ 3, "desc" ]],
                        "iDisplayLength": 500,
                        "bFilter" : true
                    });
                } );
            </script>
@stop