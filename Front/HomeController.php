<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function index()
	{


		$g_setting = DB::table('general_settings')->where('id', 1)->first();


		$sliders = DB::table('sliders')->get();
		$page_home = DB::table('page_home_items')->where('id', 1)->first();
		$why_choose_items = DB::table('why_choose_items')->get();
		$services = DB::table('services')->get();
		$testimonials = DB::table('testimonials')->get();
		$projects = DB::table('projects')->get();
		$team_members = DB::table('team_members')->get();
		$blogs = DB::table('blogs')->get();
		$social = DB::table('social_media_items')->get();
		$page_title = __('customer.home');

		session()->put('social', $social);



		// return view('pages.index');
		return view('pages.index', compact(
			'sliders',
			'page_home',
			'why_choose_items',
			'services',
			'testimonials',
			'projects',
			'team_members',
			'blogs',
			'social',
			'g_setting',
			'page_title'
		));
	}
}
