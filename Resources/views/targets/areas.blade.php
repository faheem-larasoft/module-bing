@extends('Admin::layout.main')
@section('meta_title', 'Adwords User Targeting')
@section('content')

    <div id="Content_box">
        <div id="content">
            <div style="width: 230px; float:left;">
                @include('Admin::_sidebar', ['section' => 'reporting', 'current' => 'adwords'])
            </div>
            <div style="width: 20px; float:left;">&nbsp;</div>
            <div style="width: 980px; float:left;" class="admin">

                <div class="quick-stats" style="border-bottom:0;">
                    <div class="header">
                        <strong>Adwords Targeting</strong> area

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


                <table style="width:100%;border-top:0;" id="datatable">
                    <thead>
                    <tr>
                        <td><h3 style="font-size:12px;">Country</h3></td>
                        <td><h3 style="font-size:12px;">Area</h3></td>
                        <td><h3 style="font-size:12px;">Area Code</h3></td>
                        <td><h3 style="font-size:12px;">Campaign</h3></td>
                        <td><h3 style="font-size:12px;">Targets</h3></td>
                        <td><h3 style="font-size:12px;">Clicks</h3></td>
                        <td><h3 style="font-size:12px;">Impr</h3></td>
                        <td><h3 style="font-size:12px;">CPC</h3></td>
                        <td><h3 style="font-size:12px;">Cost</h3></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>
                                {{ $row->country }}
                            </td>
                            <td>
                                {{ $row->name }}
                            </td>
                            <td>
                                {{ $row->area_code }}
                            </td>
                            <td>
                                {{ $campaigns->where('id', $row->campaign_id)->first()->name }}
                            </td>
                            <td>
                                {{ $row->count }}
                            </td>
                            <td>
                                {{ $row->clicks }}
                            </td>
                            <td>
                                {{ $row->impressions }}
                            </td>
                            <td data-sort="{{ $row->cost }}">
                                £{{ $row->clicks > 0 ? number_format($row->cost/1000000/$row->clicks, 2) : '0.00' }}
                            </td>
                            <td data-sort="{{ $row->cost }}">
                                £{{ number_format($row->cost/1000000, 2) }}
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

        $('#datatable').dataTable({
            "orderClasses": false,
            "order": [[8, "desc"]],
            "iDisplayLength": 50,
            "bLengthChange": false
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