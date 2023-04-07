<?php

namespace Modules\Adwords\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Adwords\Services\DataService;

class DeviceController extends BaseAdminController
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

        $device_data = $client->post('device-data', [
            'start'        => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'          => $this->dates['end']->format('Y-m-d H:i:s'),
            'campaign_ids' => [session('admin_adwords_hourly_campaign')],
            'day_name'     => session('admin_adwords_hourly_day'),
        ]);
        $campaigns = $client->get('campaigns');

        return view('adwords::device')->with(compact('device_data', 'campaigns'));
    }
}
