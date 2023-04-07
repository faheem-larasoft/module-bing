@extends('Admin::layout.main')
@section('meta_title', $adgroup->campaign->name .' > '.$adgroup->name.' Adgroup')
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


                    <div class="quick-stats" style="border-bottom:0;">
                        <div class="header">
                            <a href="{{ route('admin_adwords') }}">Campaigns</a> >
                            <a href="{{ route('admin_adwords_campaign', $adgroup->campaign->id) }}">{{ $adgroup->campaign->name }}</a> >
                            {{ $adgroup->name }}

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
                    <a class="create-ad" href="#"><i class="icon icon-plus orangeicon"></i> Create ad</a>
                    <form method="post" action="{{ route('admin_adwords') }}">
                        <table style="width:100%; " class="formatted" id="addatatable">
                            <thead>
                            <tr class="header">
                                <td colspan="5">
                                    <h3>
                                        Ad
                                        (<a href="#" class="remove-ad-text">remove ad text</a><a href="#" class="remove-ad-id" style="display: none;">show ad text</a>)

                                        <select name="live" style="width:50px;max-width:50px;min-width:50px;font-size:12px;padding:0;">
                                            <option value="">-</option>
                                            <option selected value="ENABLED">Live</option>
                                            <option value="PAUSED">Not live</option>
                                        </select>

                                    </h3>
                                </td>
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
                            @foreach($ads as $ad)
                                <tr @if($ad->status != 'ENABLED') style="color:grey;" @endif>
                                    <td>
                                        <input type="checkbox" name="campaigns[{{ $ad->id }}]" value="{{ $ad->id }}" style="margin:0px;">
                                    </td>
                                    <td style="max-width:280px;line-height:18px;white-space: normal;">
                                        <div class="ad-content">
                                            <div style="font-size:10px;">
                                                @if($ad->device_preference == '30001')
                                                    <i class="icon icon-mobile greyicon"></i>
                                                @endif
                                                {{ $ad->type }}
                                            </div>

                                            @if($ad->type == 'ResponsiveSearchAd')
                                                <a href="{{ $ad->url }}" target="_blank" style="color:grey;text-decoration: none;color:blue; text-decoration: underline;">
                                                    @foreach(collect($ad->assets)->where('pivot.position', 'headline')->sortByDesc('pivot.pinned') as $i => $asset)
                                                        <span style="{{ $asset->pivot->pinned ? 'color:darkblue' : '' }}">{{ $asset->text }} |</span>
                                                    @endforeach
                                                </a>
                                                <br />
                                                <span style="    background-color: #59946b;border-radius: 2px;  color: #fff; display: inline-block; font-size: 12px;  padding: 0 2px 0 2px; line-height: 14px;  vertical-align: baseline;">Ad</span>
                                                <span style="color:green;">www.myjobquote.co.uk{{ $ad->path1 ? '/'.$ad->path1 : '' }}{{ $ad->path2 ? '/'.$ad->path2 : '' }}</span>
                                                <div style="font-size:14px;color:#545454;">
                                                    @foreach(collect($ad->assets)->where('pivot.position', 'description')->sortByDesc('pivot.pinned') as $i => $asset)
                                                        <span style="{{ $asset->pivot->pinned ? 'color:black;' : '' }}">{{ $asset->text }} |</span>
                                                    @endforeach
                                                </div>
                                            @elseif($ad->type == 'TextAd')
                                                <a href="{{ $ad->url }}" target="_blank" style="color:grey;text-decoration: none;">
                                                    <span style="color:blue; text-decoration: underline;">{{ $ad->headline }}</span>
                                                </a>
                                                <br />
                                                <span style="    background-color: #59946b;border-radius: 2px;  color: #fff; display: inline-block; font-size: 12px;  padding: 0 2px 0 2px; line-height: 14px;  vertical-align: baseline;">Ad</span>
                                                <span style="color:green;">{{ $ad->display_url }}</span>
                                                <br />
                                                <span style="font-size:14px;color:#545454;">
                                    {{ $ad->description1 }}
                                                <br />
                                                {{ $ad->description2 }}
                                </span>
                                                <br />
                                            @else
                                                <a href="{{ $ad->url }}" target="_blank" style="color:grey;text-decoration: none;">
                                                    <span style="color:blue; text-decoration: underline;">{{ $ad->headline }} - {{ $ad->headline2 }}</span>
                                                </a>
                                                <br />
                                                <span style="    background-color: #59946b;border-radius: 2px;  color: #fff; display: inline-block; font-size: 12px;  padding: 0 2px 0 2px; line-height: 14px;  vertical-align: baseline;">Ad</span>
                                                <span style="color:green;">www.myjobquote.co.uk{{ $ad->path1 ? '/'.$ad->path1 : '' }}{{ $ad->path2 ? '/'.$ad->path2 : '' }}</span>
                                                <br />
                                                <span style="font-size:14px;color:#545454;">
                                    {{ $ad->description1 }}
                                </span>
                                                <br />
                                            @endif
                                            @if($ad->status == 'ENABLED')
                                                <a href="{{ route('admin_adwords_pause_ad', $ad->id) }}">Pause</a>
                                            @else
                                                <a href="{{ route('admin_adwords_enable_ad', $ad->id) }}">Enable</a>
                                            @endif
                                        </div>
                                        <div class="ad-id" style="display: none;">
                                            {{ $ad->id }}
                                        </div>
                                    </td>

                                    <td data-filter="{{ $ad->status == 'ENABLED' ? 'ENABLED' : 'PAUSED' }}">
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('clicks')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('clicks') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('cost')/$ad_data->where('ad_id', $ad->id)->sum('clicks')/100 : 0, 2) }}
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('cost')/100, 2) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('clicks') > 0 ? ($ad_data->where('ad_id', $ad->id)->sum('quotes')+$ad_data->where('ad_id', $ad->id)->sum('tradespeople'))/$ad_data->where('ad_id', $ad->id)->sum('clicks')*100 : 0, 1) }}%
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('quotes')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('quotes') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('cost')/$ad_data->where('ad_id', $ad->id)->sum('quotes')/100 : 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('tradespeople')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('tradespeople') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('cost')/$ad_data->where('ad_id', $ad->id)->sum('tradespeople')/100 : 0, 2) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('verified')) }}
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('tradespeople') > 0 ? 100*$ad_data->where('ad_id', $ad->id)->sum('verified')/$ad_data->where('ad_id', $ad->id)->sum('tradespeople') : 0, 1) }}%
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('verified') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('cost')/$ad_data->where('ad_id', $ad->id)->sum('verified')/100 : 0, 2) }}
                                    </td>
                                    <!---
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('active_memberships')) }}
                                    </td>
                                    <td>
{{ number_format($ad_data->where('ad_id', $ad->id)->sum('had_memberships')) }}
                                    </td>
                                    ---->
                                    <td class="border-left">
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('priced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('serviced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('priced') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('serviced')/$ad_data->where('ad_id', $ad->id)->sum('priced')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        {{ number_format($ad_data->where('ad_id', $ad->id)->sum('sales')) }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($ad_data->where('ad_id', $ad->id)->sum('sales_amount')/100, 2) }}
                                    </td>
                                    <td>
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('serviced') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('sales_amount')/$ad_data->where('ad_id', $ad->id)->sum('serviced')/100 : 0, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($ad_data->where('ad_id', $ad->id)->sum('sales_amount'), $ad_data->where('ad_id', $ad->id)->sum('priced_split')*$ad_data->where('ad_id', $ad->id)->sum('cost')) > 0 ? 'green' : ($ad_data->where('ad_id', $ad->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($ad_data->where('ad_id', $ad->id)->sum('sales_amount'), $ad_data->where('ad_id', $ad->id)->sum('priced_split')*$ad_data->where('ad_id', $ad->id)->sum('cost'), 1) }}%
                                    </td>
                                    <!---
                                    <td class="border-left">
                                        {{ $ad_data->where('ad_id', $ad->id)->sum('partner') }}
                                    </td>
                                    <td>
{{ $ad_data->where('ad_id', $ad->id)->sum('partner_sold') }}
                                    </td>
                                    <td>
{{ number_format($ad_data->where('ad_id', $ad->id)->sum('partner') > 0 ? $ad_data->where('ad_id', $ad->id)->sum('partner_sold')/$ad_data->where('ad_id', $ad->id)->sum('partner')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        &pound;{{ number_format($ad_data->where('ad_id', $ad->id)->sum('partner_commission')/100, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($ad_data->where('ad_id', $ad->id)->sum('partner_commission'), $ad_data->where('ad_id', $ad->id)->sum('partner_split')*$ad_data->where('ad_id', $ad->id)->sum('cost')) > 0 ? 'green' : ($ad_data->where('ad_id', $ad->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($ad_data->where('ad_id', $ad->id)->sum('partner_commission'), $ad_data->where('ad_id', $ad->id)->sum('partner_split')*$ad_data->where('ad_id', $ad->id)->sum('cost'), 1) }}%
                                    </td>
                                    --->

                                    <td class="border-left">
                                        £{{ number_format($ad_data->where('ad_id', $ad->id)->sum('quotes') > 0 ? ($ad_data->where('ad_id', $ad->id)->sum('partner_commission')+$ad_data->where('ad_id', $ad->id)->sum('sales_amount'))/$ad_data->where('ad_id', $ad->id)->sum('quotes')/100 : 0, 2) }}
                                    </td>
                                    <td class="border-left {{ $ad_data->where('ad_id', $ad->id)->sum('partner_commission')+$ad_data->where('ad_id', $ad->id)->sum('sales_amount') > $ad_data->where('ad_id', $ad->id)->sum('cost') ? 'green' : ($ad_data->where('ad_id', $ad->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($ad_data->where('ad_id', $ad->id)->sum('partner_commission')+$ad_data->where('ad_id', $ad->id)->sum('sales_amount'), $ad_data->where('ad_id', $ad->id)->sum('cost'), 1) }}%
                                    </td>
                                    <td class="{{ $ad_data->where('ad_id', $ad->id)->sum('partner_commission')+$ad_data->where('ad_id', $ad->id)->sum('sales_amount') > $ad_data->where('ad_id', $ad->id)->sum('cost') ? 'green' : ($ad_data->where('ad_id', $ad->id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        £{{ number_format(($ad_data->where('ad_id', $ad->id)->sum('partner_commission')+$ad_data->where('ad_id', $ad->id)->sum('sales_amount')-$ad_data->where('ad_id', $ad->id)->sum('cost'))/100, 2) }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($ad_data->where('ad_id', $ad->id)->sum('instant_revenue_amount')/100, 2) }}
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

                    <br />

                    <style>
                        .isok {
                            border:2px solid #62c92b !important;
                        }
                        .isbad {
                            border:2px solid red !important;
                        }
                    </style>

                    <div class="create-ad-holder" @if(!Session::has('adminAdsShowClone')) style="display:none;" @endif>

                        <div class="quick-stats" style="border-bottom:0;margin-bottom:8px;">
                            <div class="header">
                                Create Ad
                            </div>
                        </div>
                        <div class="settings-container">
                            <div class="tab-content current" id="tab-content1">
                                <form method="post" action="{{ route('admin_adwords_clone_ad', $adgroup->id) }}">

                                    @if($errors->clone->first())
                                        <p style="color:red;">
                                            @foreach($errors->clone->all() as $error)
                                                <span class="icon-attention redicon"></span> {{ $error }}<br>
                                            @endforeach
                                        </p>
                                        <br />
                                    @endif

                                    <label>
                                        Ad  Type <span style="color:#ff461c">*</span>
                                    </label>
                                    <select name="ad_type">
                                        <option value="">- pick -</option>
                                        <option value="ExpandedTextAd" {{ old('ad_type') == 'ExpandedTextAd' ? 'selected' : '' }}>Text Ad</option>
                                        <option value="ResponsiveSearchAd" {{ old('ad_type') == 'ResponsiveSearchAd' ? 'selected' : '' }}>Responsive Search Ad</option>
                                    </select>
                                    <br />

                                    <div class="ExpandedTextAd" style="{{ old('ad_type') == 'ExpandedTextAd' ? '' : 'display:none;' }};">
                                        <label>
                                            Headline  1 <span style="color:#ff461c">*</span>
                                            <br />
                                            <small>Required, max 30 chars</small>
                                        </label>
                                        <input type="text" name="headline1" value="{{ old('headline1') }}" placeholder="Headline" />
                                        <input type="text" data-copy="headline1" value="27" style="width:45px;min-width:45px !important;;text-align: center;background-color:#FBFBFB;" />
                                        <br />

                                        <label>
                                            Headline  2 <span style="color:#ff461c">*</span>
                                            <br />
                                            <small>Required, max 30 chars</small>
                                        </label>
                                        <input type="text" name="headline2" value="{{ old('headline2') }}" placeholder="Headline" />
                                        <input type="text" data-copy="headline2" value="27" style="width:45px;min-width:45px !important;;text-align: center;background-color:#FBFBFB;" />
                                        <br />

                                        <label>
                                            Description <span style="color:#ff461c">*</span>
                                            <br />
                                            <small>Required, max 80 chars</small>
                                        </label>
                                        <textarea name="description" placeholder="Description">{{ old('description') }}</textarea>
                                        <input type="text" data-copy="description" value="27" style="vertical-align:top;width:45px;min-width:45px !important;;text-align: center;background-color:#FBFBFB;" />
                                        <br />
                                    </div>

                                    <div class="ResponsiveSearchAd" style="{{ old('ad_type') == 'ResponsiveSearchAd' ? '' : 'display:none;' }};">

                                        @if(old('headlines'))
                                            @foreach(old('headlines') as $result)
                                                <div class="headlines">
                                                    <label>
                                                        Headline <span style="color:#ff461c">*</span>
                                                    </label>
                                                    <input type="text" name="headlines[]" value="{{ $result }}" placeholder="Headline" />
                                                    <br />
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="headlines">
                                                <label>
                                                    Headline <span style="color:#ff461c">*</span>
                                                </label>
                                                <input type="text" name="headlines[]" value="" placeholder="Headline" />
                                                <br />
                                            </div>
                                        @endif

                                        <div style="margin-left:210px;margin-bottom:20px;margin-top:-10px;">
                                            <a class="add-headline" style="cursor: pointer"><i class="icon icon-plus orangeicon"></i> Add headline</a>
                                        </div>

                                        @if(old('descriptions'))
                                            @foreach(old('descriptions') as $result)
                                                <div class="descriptions">
                                                    <label>
                                                        Description <span style="color:#ff461c">*</span>
                                                    </label>
                                                    <textarea name="descriptions[]" placeholder="Description">{{ $result }}</textarea>
                                                    <br />
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="descriptions">
                                                <label>
                                                    Description <span style="color:#ff461c">*</span>
                                                </label>
                                                <textarea name="descriptions[]" placeholder="Description"></textarea>
                                                <br />
                                            </div>
                                        @endif

                                        <div style="margin-left:210px;margin-bottom:20px;margin-top:-10px;">
                                            <a class="add-description" style="cursor: pointer"><i class="icon icon-plus orangeicon"></i> Add description</a>
                                        </div>
                                    </div>

                                    <label>
                                        URL  <span style="color:#ff461c">*</span>
                                        <br />
                                        <small>After ? is automatic</small>
                                    </label>
                                    <input type="text" style="width:800px" name="url" value="{{ old('url') }}" placeholder="URL" />
                                    <br />

                                    <label>
                                        Display Paths
                                        <br />
                                        <small>Optional, 15 chars each</small>
                                    </label>
                                    www.myjobquote.co.uk/<input type="text" style="width:150px;min-width:150px !important;;" name="path1" value="{{ old('path1') }}" placeholder="path1" />/<input type="text" style="width:150px;min-width:150px !important;" name="path2" value="{{ old('path2') }}" placeholder="path2" />
                                    <input type="text" data-copy="path1" value="27" style="width:45px;min-width:45px !important;;text-align: center;background-color:#FBFBFB;" />
                                    <input type="text" data-copy="path2" value="27" style="width:45px;min-width:45px !important;;text-align: center;background-color:#FBFBFB;" />
                                    <br />

                                    <label>Status  <span style="color:#ff461c">*</span></label>
                                    <select name="status">
                                        <option value="ENABLED" {{ (old('status') AND old('status') == 'ENABLED') ? 'selected' : '' }}>ENABLED</option>
                                        <option value="PAUSED" {{ (old('status') AND old('status') == 'PAUSED') ? 'selected' : '' }}>PAUSED</option>
                                    </select>
                                    <br />

                                    <input type="submit" class="update" value="Create" />
                                </form>
                            </div>
                        </div>
                        <br />
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

                        <table style="width:100%; " class="formatted" id="kwdatatable">
                            <thead>
                            <tr class="header">
                                <td colspan="5"><h3>Keyword</h3></td>
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
                            @foreach($keywords as $keyword)
                                <tr @if($keyword->status != 'ENABLED') style="color:grey;" @endif>
                                    <td>
                                        <input type="checkbox" name="campaigns[{{ $keyword->keyword_id }}]" value="{{ $keyword->keyword_id }}" style="margin:0px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin_adwords_adgroup', $keyword->keyword_id) }}" @if($keyword->status != 'ENABLED') style="color:grey;" @endif >{{ \Illuminate\Support\Str::limit($keyword->text, 30, '...') }}</a>
                                    </td>


                                    <td data-filter="{{ $keyword->status == 'ENABLED' ? 'ENABLED' : 'PAUSED' }}">
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('clicks')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('clicks') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('clicks')/100 : 0, 2) }}
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')/100, 2) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('clicks') > 0 ? ($keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople'))/$keywords->where('keyword_id', $keyword->keyword_id)->sum('clicks')*100 : 0, 1) }}%
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes')/100 : 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople')) }}
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople')/100 : 0, 2) }}
                                    </td>
                                    <td class="border-left">
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('verified')) }}
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople') > 0 ? 100*$keywords->where('keyword_id', $keyword->keyword_id)->sum('verified')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('tradespeople') : 0, 1) }}%
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('verified') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('verified')/100 : 0, 2) }}
                                    </td>
                                    <!---
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('active_memberships')) }}
                                    </td>
                                    <td>
{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('had_memberships')) }}
                                    </td>
                                    ---->
                                    <td class="border-left">
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('priced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('serviced')) }}
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('priced') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('serviced')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('priced')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        {{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('sales')) }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount')/100, 2) }}
                                    </td>
                                    <td>
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('serviced') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('serviced')/100 : 0, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount'), $keywords->where('keyword_id', $keyword->keyword_id)->sum('priced_split')*$keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')) > 0 ? 'green' : ($keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount'), $keywords->where('keyword_id', $keyword->keyword_id)->sum('priced_split')*$keywords->where('keyword_id', $keyword->keyword_id)->sum('cost'), 1) }}%
                                    </td>
                                    <!---
                                    <td class="border-left">
                                        {{ $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner') }}
                                    </td>
                                    <td>
{{ $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_sold') }}
                                    </td>
                                    <td>
{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner') > 0 ? $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_sold')/$keywords->where('keyword_id', $keyword->keyword_id)->sum('partner')*100 : 0, 0) }}%
                                    </td>
                                    <td>
                                        &pound;{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')/100, 2) }}
                                    </td>
                                    <td class="{{ returnOnInvestment($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission'), $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_split')*$keywords->where('keyword_id', $keyword->keyword_id)->sum('cost')) > 0 ? 'green' : ($keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission'), $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_split')*$keywords->where('keyword_id', $keyword->keyword_id)->sum('cost'), 1) }}%
                                    </td>
                                    --->

                                    <td class="border-left">
                                        £{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes') > 0 ? ($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount'))/$keywords->where('keyword_id', $keyword->keyword_id)->sum('quotes')/100 : 0, 2) }}
                                    </td>
                                    <td class="border-left {{ $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount') > $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') ? 'green' : ($keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        {{ returnOnInvestment($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount'), $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost'), 1) }}%
                                    </td>
                                    <td class="{{ $keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount') > $keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') ? 'green' : ($keywords->where('keyword_id', $keyword->keyword_id)->sum('cost') > 0 ? 'red' : 'yellow') }}">
                                        £{{ number_format(($keywords->where('keyword_id', $keyword->keyword_id)->sum('partner_commission')+$keywords->where('keyword_id', $keyword->keyword_id)->sum('sales_amount')-$keywords->where('keyword_id', $keyword->keyword_id)->sum('cost'))/100, 2) }}
                                    </td>
                                    <td>
                                        &pound;{{ number_format($keywords->where('keyword_id', $keyword->keyword_id)->sum('instant_revenue_amount')/100, 2) }}
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


                    <br /><br />

                </div>
            </div>
        </div>


        <div id="dialog-table-columns" class="dialogaction">
            <div class="diaglog-close-holder">
                <span class="dialogclose icon-cancel-squared"></span>
            </div>

            <h1>Manage Table Columns</h1>

            <style>
                #dialog-table-columns td, #dialog-table-columns th, #dialog-table-columns table {
                    border:1px solid #ddd;
                    border-collapse: collapse;
                }
                #dialog-table-columns input[type=checkbox] {
                    width:12px;
                    height:12px;
                }
            </style>
            <table width="75%;" style="margin:auto;">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Use</th>
                    <th>Definition</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Keyword
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="status">
                    </td>
                    <td>
                        The status of this keyword.
                    </td>
                </tr>
                <tr>
                    <td>
                        Click Data
                    </td>
                    <td>
                        <input type="checkbox" name="click_data">
                    </td>
                    <td>
                        Clicks, Impressions, CTR, CPC, BID
                    </td>
                </tr>
                <tr>
                    <td>
                        Cost
                    </td>
                    <td>
                        <input type="checkbox" name="cost">
                    </td>
                    <td>
                        Adwords spend.
                    </td>
                </tr>
                <tr>
                    <td>
                        Average Pos.
                    </td>
                    <td>
                        <input type="checkbox" name="avg_position">
                    </td>
                    <td>
                        Average position in search.
                    </td>
                </tr>
                <tr>
                    <td>
                        Quote Total
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        Number of quotes created.
                    </td>
                </tr>
                <tr>
                    <td>
                        Quotes (Priced)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        Quotes which were priced.
                    </td>
                </tr>
                <tr>
                    <td>
                        Quotes (Partner)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        Quotes which were sent to a partner.
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Commission (Ex. VAT)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The commission of sold quotes both to partner and internal.
                    </td>
                </tr>
                <tr>
                    <td>
                        Commission (Ex. VAT) (Priced)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The commission of quotes sold internally.
                    </td>
                </tr>
                <tr>
                    <td>
                        Commission (External)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The commission of quotes sold to a partner.
                    </td>
                </tr>
                <tr>
                    <td>
                        Tradespeople
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        Tradespeople who registered.
                    </td>
                </tr>
                <tr>
                    <td>
                        Tradespeople (Active Email)
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <input type="checkbox" name="adgroup">
                    </td>
                    <td>
                        The name of this adgroup.
                    </td>
                </tr>
                </tbody>
            </table>

            <br />

            <input type="submit" value="Save" />

            <div class="clear"></div>

            <a class="dialogclose" href="#">Click to close this dialog and cancel your current action</a>
        </div>
@stop


@section('javascript')
    <script>
        $(document).ready(function() {
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


            var tablead = $('#addatatable').DataTable({
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

                    this.api().columns(2).search("ENABLED").draw();

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

            $("select[name=live]").change(function() {
                tablead.columns(2).search($("select[name=live]").val()).draw();
            });

            $(".remove-ad-text").click(function() {
                $(this).hide();
                $(".ad-content").hide();
                $(".ad-id").show();
                $(".remove-ad-id").show();
                tablead.columns.adjust().draw();
            });

            $(".remove-ad-id").click(function() {
                $(this).hide();
                $(".ad-content").show();
                $(".ad-id").hide();
                $(".remove-ad-text").show();
                tablead.columns.adjust().draw();
            });


            var table = $('#kwdatatable').DataTable({
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

            $("#date-range").daterangepicker("setRange", {
                start: moment("{{ $dates['start']->format('Y-m-d') }}").toDate(),
                end: moment("{{ $dates['end']->copy()->subDay()->format('Y-m-d') }}").toDate()
            });

            $('#datatable2').dataTable( {
                "orderClasses": false,
                "order": [[ 2, "desc" ]],
                "iDisplayLength": 50,
                "bLengthChange": false,
                "deferRender": true,
                "bFilter" : false
            });

            $('#datatable').dataTable( {
                "orderClasses": false,
                "order": [[ 1, "asc" ]],
                "iDisplayLength": 50,
                "bLengthChange": false,
                "deferRender": true,
                "bFilter" : false
            });

            $(".ads-dropdown .icon-down-open-1").click(function() {
                $(".ads-table").fadeIn();
                $(".ads-dropdown .icon-down-open-1").hide();
                $(".ads-dropdown .icon-up-open-1").show();
            });

            $(".ads-dropdown .icon-up-open-1").click(function() {
                $(".ads-table").fadeOut();
                $(".ads-dropdown .icon-down-open-1").show();
                $(".ads-dropdown .icon-up-open-1").hide();
            });


            $("input[name=headline1]").on('change keyup keydown', function() {
                $("input[data-copy=headline1]").val($(this).val().length);
                $("input[data-copy=headline1]").removeClass('isbad').removeClass('isok');

                if($(this).val().length > 30) {
                    $("input[data-copy=headline1]").addClass('isbad');
                } else {
                    $("input[data-copy=headline1]").addClass('isok');
                }
            });

            $("input[name=headline2]").on('change keyup keydown', function() {
                $("input[data-copy=headline2]").val($(this).val().length);
                $("input[data-copy=headline2]").removeClass('isbad').removeClass('isok');

                if($(this).val().length > 30) {
                    $("input[data-copy=headline2]").addClass('isbad');
                } else {
                    $("input[data-copy=headline2]").addClass('isok');
                }
            });

            $("textarea[name=description]").on('change keyup keydown', function() {
                $("input[data-copy=description]").val($(this).val().length);
                $("input[data-copy=description]").removeClass('isbad').removeClass('isok');

                if($(this).val().length > 80) {
                    $("input[data-copy=description]").addClass('isbad');
                } else {
                    $("input[data-copy=description]").addClass('isok');
                }
            });

            $("input[name=path1]").on('change keyup keydown', function() {
                $("input[data-copy=path1]").val($(this).val().length);
                $("input[data-copy=path1]").removeClass('isbad').removeClass('isok');

                if($(this).val().length > 15) {
                    $("input[data-copy=path1]").addClass('isbad');
                } else {
                    $("input[data-copy=path1]").addClass('isok');
                }
            });

            $("input[name=path2]").on('change keyup keydown', function() {
                $("input[data-copy=path2]").val($(this).val().length);
                $("input[data-copy=path2]").removeClass('isbad').removeClass('isok');

                if($(this).val().length > 15) {
                    $("input[data-copy=path2]").addClass('isbad');
                } else {
                    $("input[data-copy=path2]").addClass('isok');
                }
            });

            $(".create-ad").click(function() {
                $(".create-ad-holder").show();
                $('html, body').animate({scrollTop:$(".create-ad-holder").offset().top - 20});
            });

            @if(old('ad_type'))
            $('html, body').animate({scrollTop:$(".create-ad-holder").offset().top - 20});
            @endif

            $("select[name=ad_type]").change(function() {
                $(".ExpandedTextAd,.ResponsiveSearchAd").hide();

                if($(this).val() == 'ExpandedTextAd') {
                    $(".ExpandedTextAd,.ResponsiveSearchAd").show();
                } else {
                    $(".ResponsiveSearchAd").show();
                }
            });


            $(".add-description").click(function() {
                $(".descriptions").append('\n' +
                    '                <label>\n' +
                    '                Description <span style="color:#ff461c">*</span>\n' +
                    '                </label>\n' +
                    '                <textarea name="descriptions[]" placeholder="Description"></textarea><br />');
            });

            $(".add-headline").click(function() {
                $(".headlines").append('\n' +
                    '                <label>\n' +
                    '                Headline <span style="color:#ff461c">*</span>\n' +
                    '                </label>\n' +
                    '                <input type="text" name="headlines[]" value="" placeholder="Headline" /><br />');
            });
        } );
    </script>
@stop
