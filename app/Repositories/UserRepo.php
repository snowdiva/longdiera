<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserRepo
{
    protected $userTable = 'user';

    public function userInfo($userId)
    {
        $user = DB::table($this->userTable)
            ->where('id', $userId)
            ->first();

        return [
            'user_id' => $user->id,
            'username' => $user->username,
            'alias' => $user->alias,
            'gender' => $user->gender,
            'status' => $user->status
        ];
    }

    public function checkUser($userName, $password = '')
    {
        $where = [['username', '=', $userName]];
        if ('' !== $password) $where[] = ['password', '=', md5(md5($password))];

        $user = DB::table($this->userTable)
            ->where($where)
            ->first();

        return $user;
    }

    public function getData($where = [])
    {
        if (empty($where)) {
            $list = DB::table($this->userTable)
                ->paginate(15);
        } else {
            $list = DB::table($this->userTable)
                ->where($where)
                ->paginate(15);
        }

        return $list;
    }
}