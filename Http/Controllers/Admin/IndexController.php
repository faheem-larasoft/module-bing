<?php

namespace Modules\Bing\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Bing\Services\DataService;

class IndexController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->dates = Session::get('admin-bing-dates');

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

    public function index()
    {
        $client = new DataService();

        $index_data = $client->post('index-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);
        $campaigns = $index_data['campaigns'];
        $spend = $index_data['spend'];
        $statistics = $index_data['statistics'];

        return view('bing::admin.index')->with(compact('campaigns', 'spend', 'statistics'));
    }

    public function campaign($id)
    {
        $client = new DataService();

        $index_data = $client->post('campaign-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);

        $campaign = $index_data['campaign'];
        $adgroups = $index_data['adgroups'];
        $spend = $index_data['spend'];
        $statistics = $index_data['statistics'];

        return view('bing::admin.campaign')->with(compact('campaign', 'adgroups', 'spend', 'statistics'));
    }

    public function adgroup($id)
    {
        $client = new DataService();

        $index_data = $client->post('adgroup-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);

        $adgroup = $index_data['adgroup'];
        $keywords = $index_data['keywords'];
        $spend = $index_data['spend'];
        $statistics = $index_data['statistics'];

        return view('bing::admin.adgroup')->with(compact('adgroup', 'keywords', 'spend', 'statistics'));
    }
}
