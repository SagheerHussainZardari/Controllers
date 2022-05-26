<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationEmailToCustomer;
use App\Models\Customer;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->session()->has('customer_status')) {
                return redirect()->route('customer.dashboard');
            }
            return $next($request);
        });
    }
    public function index(Request $request)
    {


        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $page_title = __('customer.registration');
        return view('customer.auth.registration', compact('g_setting', 'page_title'));
    }

    public function store(Request $request)
    {

        if (Session()->get('Tries') > 4) {
            $current_time = date('Y-m-d H:i:s');
            $pre_time = Session()->get('limitTime');

            if ($current_time > $pre_time) {
                Session()->forget('Tries');
                Session()->forget('limitTime');
            } else {
                return redirect()->back()->with('error', 'You have exceeded the number of attempts. Please try again later. After ' . Session()->get('limitTime'));
            }
        }

        if (Session()->get('Tries') > 0) {
            $limitTime = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            Session()->put('limitTime', $limitTime);
            Session()->save();

            $tries = Session()->get('Tries');
            $tries = $tries + 1;

            Session()->put('Tries', $tries);
            Session()->save();
        } else {
            Session()->put('Tries', 1);
            Session()->save();
        }

        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $check_email = DB::table('customers')->where('customer_email', $request->customer_email)->first();

        if ($check_email) {
            return redirect()->back()->with('error', 'Email already exists');
        } else {


            $customer = new Customer();
            $data = $request->only($customer->getFillable());



            unset($request->customer_re_password);
            $data['customer_password'] = FacadesHash::make($request->customer_password);
            $data['customer_phone'] = '';
            $data['customer_country'] = '';
            $data['customer_address'] = '';
            $data['customer_state'] = '';
            $data['customer_city'] = '';
            $data['customer_zip'] = '';
            $data['customer_name'] = '';
            $data['customer_email'] = $request->customer_email;

            $data['customer_token'] = '';

            $customer->fill($data)->save();

            $customer_id = $customer->id;


            $data['customer_status'] = 'Active';
            DB::table('customers')->where('id', $customer_id)->update($data);


            session(['customer_id' => $customer->id]); // customer_id is not a field in the customers table. It is just used to avoid conflict with the admin id.

            session(['customer_name' => $customer->customer_name]);
            session(['customer_email' => $customer->customer_email]);
            session(['customer_phone' => $customer->customer_phone]);
            session(['customer_country' => $customer->customer_country]);
            session(['customer_address' => $customer->customer_address]);
            session(['customer_state' => $customer->customer_state]);
            session(['customer_city' => $customer->customer_city]);
            session(['customer_zip' => $customer->customer_zip]);
            session(['customer_password' => $customer->customer_password]);
            session(['customer_status' => 'Active']);

            return redirect()->route('customer.dashboard');
        }





        // $token = hash('sha256', time());








        // //check if email already exits

        // $customer_email = DB::table('customers')->where('customer_email', $request->customer_email)->first();

        // if ($customer_email) {

        //     return redirect()->back()->with('error', 'Email already exists');
        // } else {

        //     

        //     // Send Email
        //     $email_template_data = DB::table('email_templates')->where('id', 6)->first();
        //     $subject = $email_template_data->et_subject;
        //     $message = $email_template_data->et_content;


        //     //get current server url


        //     $verification_link = url('customer/registration/verify/' . $token . '/' . $request->customer_email);


        //     $message = str_replace('[[verification_link]]', $verification_link, $message);


        //     return redirect($verification_link);
        // }
    }

    // public function verify()
    // {
    //     $email_from_url = request()->segment(count(request()->segments()));
    //     $aa = DB::table('customers')->where('customer_email', $email_from_url)->first();

    //     if (!$aa) {
    //         return redirect()->route('customer.login');
    //     }

    //     $expected_url = url('customer/registration/verify/' . $aa->customer_token . '/' . $aa->customer_email);
    //     $current_url = url()->current();
    //     if ($expected_url != $current_url) {
    //         return redirect()->route('customer.login');
    //     }

    //     $data['customer_status'] = 'Active';
    //     $data['customer_token'] = '';
    //     Customer::where('customer_email', $email_from_url)->update($data);

    //     return redirect()->route('customer.login')->with('success', 'Registration is completed. You can now login.');
    // }
}
