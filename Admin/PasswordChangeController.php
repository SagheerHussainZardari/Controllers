<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Hash;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $admin_data = Admin::where('id', 1)->first();
        return view('admin.auth.password_change', compact('admin_data'));
    }

    public function update(Request $request)
    {

        if ($request->password == $request->re_password) {


            $data['password'] = Hash::make($request->password);
            Admin::where('id', 1)->update($data);

            return redirect()->back()->with('success', 'Password is updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Password and Re-Password is not matched!');
        }
    }
}
