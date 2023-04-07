<?php

namespace Modules\Adwords\Http\Controllers\Admin\Campaigns;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\SummaryTables\QPTSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Adwords\Services\DataService;

class CampaignController extends BaseAdminController
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

        $daily_data = $client->get('campaign-data-daily');
        $campaigns = $client->get('campaigns');
        $data = $client->post('campaign-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s')
        ]);

        $query = QPTSummary::where('date', '>=', $this->dates['start']);
        $query->where('date', '<=', $this->dates['end']);
        $query->selectRaw('category_id, sum(quotes) as quotes, sum(active_trades) as active_trades, avg(active_trades) as avg_active_trades, sum(active_jobs) as active_jobs');
        $query->groupBy('category_id');
        $qptByCategory = $query->get();
        
        return view('adwords::index')->with(compact('campaigns', 'data', 'daily_data', 'qptByCategory'));
    }

    public function campaign($campaign)
    {
        $client = new DataService();

        $campaign = $client->get('campaigns/' . $campaign, false);
        $adgroups = $client->get('campaigns/' . $campaign->id . '/adgroups');
        $data = $client->post('adgroups-data', [
            'start' => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'   => $this->dates['end']->format('Y-m-d H:i:s'),
            'id'    => $adgroups->pluck('id')->toArray()
        ]);

        return view('adwords::campaign')->with(compact('adgroups', 'campaign', 'data'));
    }

    public function status()
    {
        $client = new DataService();

        $action = $client->post('campaigns-batch', [
            'action'    => request('action'),
            'campaigns' => request('campaigns'),
            'user_id'   => auth()->user()->id
        ]);

        if (isset($action['errors'])) {
            return back()->with('flashError', firstError($action['errors']));
        }

        $action = request('action') == 'enable' ? 'ENABLED' : 'PAUSED';

        return back()->with('flashSuccess', count(request('campaigns')) . ' Campaigns were successfully ' . $action);
    }
}
