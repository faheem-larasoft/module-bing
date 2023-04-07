<?php

namespace Modules\Adwords\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Adwords\Services\DataService;

class RegionController extends BaseAdminController
{
    private $dates;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->dates = Session::get('admin-adwords-dates');

            if (!isset($this->dates['start'])) {
                $this->dates = [
                    'start'   => Carbon::today()->startOfDay(),
                    'end'     => Carbon::tomorrow()->startOfDay(),
                    'display' => 'Today'
                ];
            }
            view()->share('dates', $this->dates);

            return $next($request);
        });
    }

    public function view()
    {
        $client = new DataService();

        $data = $client->post('region-data', [
            'start'        => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'          => $this->dates['end']->format('Y-m-d H:i:s'),
            'campaign_ids' => [session('admin_adwords_hourly_campaign')],
        ]);
		
        $region_data = collect($data['data']);
        $modifiers = collect($data['modifiers']);
        $modifiers_previous = collect($data['modifiers_previous']);
        $campaigns = $client->get('campaigns');
        $regions = $client->get('regions');

        return view('adwords::regions')->with(compact('region_data', 'campaigns', 'regions', 'modifiers', 'modifiers_previous'));
    }

    public function save()
    {
        $client = new DataService();

        $client->post('region-bids', [
            'campaign_id' => session('admin_adwords_hourly_campaign'),
            'bids'        => array_filter(request()->all()['regions'])
        ]);

        return back()->with('flashSuccess', 'Bids have been updated.');
    }
}
