<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home()
    {
        $data['categories'] = Category::where('service_provider_id', auth()->user()->id)->count();
        $data['items'] = Item::where('service_provider_id', auth()->user()->id)->count();
        return view('service_providers.home', $data);
    }
}