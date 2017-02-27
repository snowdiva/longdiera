<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class HomeController extends AdminController
{
    public function dashboard(Request $request)
    {
//        dump(Request::getRequestUri());die;
//        dump(Session::get('user'));die;
//        dump(public_path());die;
        return view('admin.dashboard');
    }
}