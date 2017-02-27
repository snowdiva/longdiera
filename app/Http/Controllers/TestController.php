<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class TestController extends CommonController
{
    public function initAdmin(Request $request)
    {
        if (!DB::table('user')->where('id', 1)->first() && !DB::table('group')->where('id', 1)->first()) {
            DB::table('user')
                ->insert([
                    'id' => 1,
                    'username' => 'superadmin',
                    'email' => 'super@email.com',
                    'phone' => '',
                    'gender' => 0,
                    'password' => md5(md5('123123')),
                    'group_id' => 1,
                    'alias' => '管理员',
                    'create_at' => time(),
                    'status' => 1
                ]);

            DB::table('group')
                ->insert([
                    'id' => 1,
                    'name' => '管理员组',
                    'explain' => '后台管理员',
                    'amount' => 1,
                    'create_at' => time(),
                    'status' => 1
                ]);

            DB::table('table')
                ->insert([
                    [
                        'name' => 'user',
                        'count' => 1
                    ],
                    [
                        'name' => 'group',
                        'count' => 1
                    ]
                ]);
            return '初始化完成';
        } else {
            return '已初始化';
        }
    }
}