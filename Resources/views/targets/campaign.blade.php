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

                <div class="quick-stats">
                    <div class="header">
                        <a href="{{ URL::route('admin_adwords') }}">Campaigns</a> >
                        {{ $campaign->name }} > Targets
                    </div>
                </div>

                <br />

                <style>
                    html, body, #map-canvas {
                        height: 100%;
                        margin: 0px;
                        padding: 0px
                    }
                    #panel {
                        position: absolute;
                        top: 5px;
                        left: 50%;
                        margin-left: -180px;
                        z-index: 5;
                        background-color: #fff;
                        padding: 5px;
                        border: 1px solid #999;
                    }
                </style>

                <div id="map-canvas" style="width:1100px;height:700px;"></div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: 11,
                center: {lat: 51.507, lng: 0.1278}
            });
            @foreach($files as $file)
            new google.maps.KmlLayer({
                url: '{{ config('filesystems.disks.s3-public.url') }}{{ $file }}',
                map: map
            });
            @endforeach
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyBlmG5v2zyTFQtR801pd3TmpzQ4lXIhHDc">
    </script>
@stop