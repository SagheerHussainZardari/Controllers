<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TeamMemberController extends Controller
{
    public function index()
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $team_member = DB::table('page_team_items')->where('id', 1)->first();
        $team_members = DB::table('team_members')->paginate(8);

        $page_title = __('customer.team_members');

        return view('pages.team_members', compact('team_member', 'g_setting', 'team_members', 'page_title'));
    }

    public function detail($slug)
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $team_member_detail = DB::table('team_members')->where('slug', $slug)->first();
        if (!$team_member_detail) {
            return abort(404);
        }
        $team_members = DB::table('team_members')->get();

        $page_title = __('customer.team_members');
        return view('pages.team_member_detail', compact('g_setting', 'team_member_detail', 'team_members', 'page_title'));
    }

    public function teaching_method()
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();

        $page_title = __('customer.teachingmethod');

        return view('pages.teachingmethod', compact('g_setting', 'page_title'));
    }
}
