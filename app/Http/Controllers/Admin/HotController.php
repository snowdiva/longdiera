<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\HotRepo;
use Illuminate\Http\Request;

class HotController extends AdminController
{
    public function index(Request $request, HotRepo $hotRepo)
    {
        dd(\Illuminate\Support\Facades\Request::route());
        // TODO::查询条件重组

        $list = $hotRepo->getAll();

        return view('admin.hot', ['list' => $list]);
    }

    public function hotAdd()
    {

    }

    public function hotAddPost()
    {

    }

    public function hotEdit()
    {

    }

    public function hotEditPost()
    {

    }
}