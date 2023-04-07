<?php

namespace Modules\Adwords\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Adwords\Services\DataService;

class QptController extends BaseAdminController
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

    /**
     * @return mixed
     */
    public function index()
    {
        $client = new DataService();

        $data = $client->post('monthly-data', [
            'start'        => $this->dates['start']->format('Y-m-d H:i:s'),
            'end'          => $this->dates['end']->format('Y-m-d H:i:s')
        ]);
        $campaigns = $client->get('campaigns');

        $categories = Category::with('campaignPivots', 'children.campaignPivots')->parents()->get();

        $buying_trades = collect(DB::select('SELECT job, count(*) as count FROM (
    SELECT quotes.job, users_quotes.user_id, count(*) as count FROM `users_quotes`
INNER JOIN quotes on quotes.id = users_quotes.quote_id
WHERE `users_quotes`.`accepted_at` is NOT NULL
AND `users_quotes`.`accepted_at` >= ?
AND `users_quotes`.`accepted_at` < ?
GROUP BY quotes.job, users_quotes.user_id
    ) as t group by job', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->startOfMonth()
        ]));

        $buying_trades_2 = collect(DB::select('SELECT job, count(*) as count FROM (
    SELECT quotes.job, users_quotes.user_id, count(*) as count FROM `users_quotes`
INNER JOIN quotes on quotes.id = users_quotes.quote_id
WHERE `users_quotes`.`accepted_at` is NOT NULL
AND `users_quotes`.`accepted_at` >= ?
AND `users_quotes`.`accepted_at` < ?
GROUP BY quotes.job, users_quotes.user_id
    ) as t group by job', [
            Carbon::now()->subMonths(2)->startOfMonth(),
            Carbon::now()->subMonths(1)->startOfMonth()
        ]));

        $buying_trades_3 = collect(DB::select('SELECT job, count(*) as count FROM (
    SELECT quotes.job, users_quotes.user_id, count(*) as count FROM `users_quotes`
INNER JOIN quotes on quotes.id = users_quotes.quote_id
WHERE `users_quotes`.`accepted_at` is NOT NULL
AND `users_quotes`.`accepted_at` >= ?
AND `users_quotes`.`accepted_at` < ?
GROUP BY quotes.job, users_quotes.user_id
    ) as t group by job', [
            Carbon::now()->subMonths(3)->startOfMonth(),
            Carbon::now()->subMonths(2)->startOfMonth()
        ]));

        $qpt = [];

        foreach ($campaigns as $campaign) {
            $category_ids = collect($campaign->category_pivots)->pluck('category_id')->toArray();

            $quotes_priced = $data->where('campaign_id', $campaign->id)->sum('priced');
            $quotes_total = $data->where('campaign_id', $campaign->id)->sum('quotes');
            $spend = $data->where('campaign_id', $campaign->id)->sum('spend');
            $cpc = $quotes_total > 0 ? $spend / 100 / $quotes_total : 0;

            $priced_portion = $quotes_priced * $cpc;

            $qpt[$campaign->id] = [
                'last_1_month'   => [
                    'qpt'           => 0,
                    'diff'          => 0,
                    'quotes'        => 0,
                    'buying_trades' => 0
                ],
                'last_2_month'   => [
                    'qpt'           => 0,
                    'diff'          => 0,
                    'quotes'        => 0,
                    'buying_trades' => 0
                ],
                'last_3_month'   => [
                    'qpt'           => 0,
                    'diff'          => 0,
                    'quotes'        => 0,
                    'buying_trades' => 0
                ],
                'revenue'        => 0,
                'spend'          => $spend / 100,
                'quotes'         => $quotes_total,
                'priced'         => $quotes_priced,
                'internal_spend' => $priced_portion,
                'cpc'            => $cpc,
                'optimum'        => 0
            ];

            $qpt[$campaign->id]['revenue'] += $data->where('campaign_id', $campaign->id)->sum('partner_commission')+$data->where('campaign_id', $campaign->id)->sum('sales_amount');

            $qpt[$campaign->id]['last_1_month']['quotes'] += $data->where('campaign_id', $campaign->id)->sum('priced');
            $qpt[$campaign->id]['last_1_month']['buying_trades'] += $buying_trades->whereIn('job', $category_ids)
                                                                                  ->sum('count');

            $qpt[$campaign->id]['last_2_month']['quotes'] += $data->where('campaign_id', $campaign->id)->sum('priced');
            $qpt[$campaign->id]['last_2_month']['buying_trades'] += $buying_trades_2->whereIn('job', $category_ids)
                                                                                    ->sum('count');

            $qpt[$campaign->id]['last_2_month']['quotes'] += $data->where('campaign_id', $campaign->id)->sum('priced');
            $qpt[$campaign->id]['last_3_month']['buying_trades'] += $buying_trades_3->whereIn('job', $category_ids)
                                                                                    ->sum('count');

            $qpt[$campaign->id]['last_1_month']['qpt'] = number_format($qpt[$campaign->id]['last_1_month']['buying_trades'] > 0 ? $qpt[$campaign->id]['last_1_month']['quotes'] / $qpt[$campaign->id]['last_1_month']['buying_trades'] : 0,
                2);
            $qpt[$campaign->id]['last_2_month']['qpt'] = number_format($qpt[$campaign->id]['last_2_month']['buying_trades'] > 0 ? $qpt[$campaign->id]['last_2_month']['quotes'] / $qpt[$campaign->id]['last_2_month']['buying_trades'] : 0,
                2);
            $qpt[$campaign->id]['last_3_month']['qpt'] = number_format($qpt[$campaign->id]['last_3_month']['buying_trades'] > 0 ? $qpt[$campaign->id]['last_3_month']['quotes'] / $qpt[$campaign->id]['last_3_month']['buying_trades'] : 0,
                2);
            $qpt[$campaign->id]['optimum'] = number_format(($qpt[$campaign->id]['internal_spend'] * $qpt[$campaign->id]['last_1_month']['buying_trades']) > 0 ? (($qpt[$campaign->id]['revenue'] * $qpt[$campaign->id]['last_1_month']['quotes']) / ($qpt[$campaign->id]['internal_spend'] * $qpt[$campaign->id]['last_1_month']['buying_trades'])) : 0,
                2);

            $qpt[$campaign->id]['last_1_month']['diff'] = abs(round($qpt[$campaign->id]['last_1_month']['qpt'] > 0 ? ($qpt[$campaign->id]['last_1_month']['qpt'] - $qpt[$campaign->id]['optimum']) / ($qpt[$campaign->id]['last_1_month']['qpt']) * 100 : 0));
            $qpt[$campaign->id]['last_2_month']['diff'] = abs(round($qpt[$campaign->id]['last_2_month']['qpt'] > 0 ? ($qpt[$campaign->id]['last_2_month']['qpt'] - $qpt[$campaign->id]['optimum']) / ($qpt[$campaign->id]['last_2_month']['qpt']) * 100 : 0));
            $qpt[$campaign->id]['last_3_month']['diff'] = abs(round($qpt[$campaign->id]['last_3_month']['qpt'] > 0 ? ($qpt[$campaign->id]['last_3_month']['qpt'] - $qpt[$campaign->id]['optimum']) / ($qpt[$campaign->id]['last_3_month']['qpt']) * 100 : 0));

            if ($qpt[$campaign->id]['spend'] * $qpt[$campaign->id]['last_1_month']['buying_trades'] == 0) {
                unset($qpt[$campaign->id]);
            }
        }

        return view('adwords::qpt')->with(compact('categories', 'qpt', 'campaigns'))->with('dates', $this->dates);
    }
}
