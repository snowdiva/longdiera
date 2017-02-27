<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\GroupRepo;

class GroupController extends AdminController
{
    public function index(GroupRepo $groupRepo)
    {
        $list = $groupRepo->getAll();

        return view('admin.group', ['list' => $list]);
    }

    public function getList(GroupRepo $groupRepo)
    {
        $list = $groupRepo->getList();

        return $list;
    }
}