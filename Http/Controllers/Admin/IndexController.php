<?php

namespace Modules\Bing\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
            'timezone' => Auth::user()->timezone,
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);
        $campaigns = collect($index_data['campaigns']);
        $spend = collect($index_data['spend']);
        $statistics = collect($index_data['statistics']);

        return view('bing::admin.index')->with(compact('campaigns', 'spend', 'statistics'));
    }

    public function campaign($id)
    {
        $client = new DataService();

        $index_data = $client->post('campaign-data', [
            'id'    => $id,
            'timezone' => Auth::user()->timezone,
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);

        $campaign = $index_data['campaign'];
        $adgroups = collect($index_data['adgroups']);
        $spend = collect($index_data['spend']);
        $statistics = collect($index_data['statistics']);

        return view('bing::admin.campaign')->with(compact('campaign', 'adgroups', 'spend', 'statistics'));
    }

    public function adgroup($id)
    {
        $client = new DataService();

        $index_data = $client->post('adgroup-data', [
            'id'    => $id,
            'timezone' => Auth::user()->timezone,
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);

        $adgroup = $index_data['adgroup'];
        $keywords = collect($index_data['keywords']);
        $spend = collect($index_data['spend']);
        $statistics = collect($index_data['statistics']);

        return view('bing::admin.adgroup')->with(compact('adgroup', 'keywords', 'spend', 'statistics'));
    }
}
