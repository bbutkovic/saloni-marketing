<?php

namespace App\Http\Controllers;

use FacebookAds\Exception\Exception;
use Illuminate\Http\Request;
use App\Repositories\{FacebookRepository};

use App;
use Illuminate\Support\Facades\Auth;


class FacebookController extends Controller
{

    public function __construct() {
        $this->facebook_repo = new App\Repositories\FacebookRepository;
    }

    public function getFacebookCampaignManagement() {

        $salon = App\Models\Salons::find(Auth::user()->salon_id);

        return view('campaigns.facebook', ['salon' => $salon]);
    }

    public function createFacebookCampaign(Request $request) {
        $campaign = $this->facebook_repo->createNewFacebookCampaign($request->all());

        return $campaign;
    }
}