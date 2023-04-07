<?php

namespace Modules\Adwords\Http\Controllers\Admin\Adgroups;

use App\Http\Controllers\Admin\BaseAdminController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Adwords\Services\DataService;

class AdgroupController extends BaseAdminController
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

    public function index()
    {
        $client = new DataService();

        $adgroups = $client->get('adgroups');
        $data = $client->post('adgroups-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s'),
            'id'    => $adgroups->pluck('id')->toArray()
        ]);

        return view('adwords::adgroups')->with(compact('adgroups', 'data'));
    }

    public function view($adgroup)
    {
        $client = new DataService();

        $adgroup = $client->get('adgroups/' . $adgroup, false);
        $ads = $client->get('adgroups/' . $adgroup->id . '/ads');

        $ad_data = $client->post('ads-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s'),
            'id'    => $ads->pluck('id')->toArray()
        ]);

        $keywords = $client->post('keywords-data', [
            'start'      => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'        => $this->dates['end']->format('Y-m-d H:i:s'),
            'adgroup_id' => $adgroup->id
        ]);

        return view('adwords::adgroup')->with(compact('adgroup', 'ads', 'ad_data', 'keywords'));
    }
}