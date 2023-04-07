<?php

namespace Modules\Adwords\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Modules\Adwords\Services\DataService;

class AdController extends BaseAdminController
{
    public function cloneAd($adgroup)
    {
        $client = new DataService();

        $ad = $client->post('adgroups/' . $adgroup . '/ads', request()->all());

        if (isset($ad['errors'])) {
            return back()
                ->withErrors($ad['errors'], 'clone')
                ->with('adminAdsShowClone', 1)
                ->withInput()
                ->with('flashError', 'Please correct the errors shown below and try again.');
        }

        return back()->with('flashSuccess', 'Your ad has been created successfully.');
    }

    public function pause($ad)
    {
        $client = new DataService();

        $response = $client->post('ads/' . $ad, [
            'user_id' => auth()->user()->id,
            'status'  => 'pause'
        ]);

        if (isset($response['success'])) {
            return back()->with('flashSuccess', 'You have successfully paused the ad.');
        } else {
            return back()->with('flashError', firstError($response['errors']));
        }
    }

    public function enable($ad)
    {
        $client = new DataService();

        $response = $client->post('ads/' . $ad, [
            'user_id' => auth()->user()->id,
            'status'  => 'enable'
        ]);

        if (isset($response['success'])) {
            return back()->with('flashSuccess', 'You have successfully enabled the ad.');
        } else {
            return back()->with('flashError', firstError($response['errors']));
        }
    }
}
