<?php

namespace App\Http\Controllers;

use App\Repositories\ArtiRepo;
use App\Repositories\SortRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class HomeController extends CommonController
{
    public function index(Request $request, ArtiRepo $artiRepo, SortRepo $sortRepo)
    {
        $list = $artiRepo->getFontArticleList();

        return view($this->themeName . 'home', [
            'list' => $list
        ]);
    }
}