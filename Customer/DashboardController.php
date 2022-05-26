<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $orders = DB::table('orders')->where('customer_id', session()->get('customer_id'))->get();

        $page_title = __('customer.dashboard');

        return view('customer.pages.dashboard', compact('g_setting', 'orders', 'page_title'));
    }
}
