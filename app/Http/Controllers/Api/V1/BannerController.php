<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function get_customer_banner(Request $request)
    {
        $banners = Banner::select('title', 'image', 'url', 'receiver')->customerAndAll()->active()->get();
        return $banners;
    }

    public function get_agent_banner(Request $request)
    {
        $banners = Banner::select('title', 'image', 'url', 'receiver')->agentAndAll()->active()->get();
        return $banners;
    }
}
