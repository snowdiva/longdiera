<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserRepo
{
    protected $userTable = 'user';

    public function userInfo ($userId)
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

    public function checkUserById ($id)
    {
        $user = DB::table($this->userTable)
            ->where('id', '=', $id)
            ->first();

        return $user;
    }

    public function checkUser ($userName, $password = '')
    {
        $where = [['username', '=', $userName]];
        if ('' !== $password) $where[] = ['password', '=', md5($password)];

        $user = DB::table($this->userTable)
            ->where($where)
            ->first();

        return $user;
    }

    public function getData ($where = [])
    {
        $list = DB::table($this->userTable)
            ->select('id', 'username', 'email', 'phone' ,'group_id' ,'alias' ,'create_at' ,'gender' ,'status')
            ->where($where)
            ->orderBy('create_at', 'DESC')
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $list;
    }

    public function insertData (Array $user)
    {
        $id = DB::table($this->userTable)
            ->insertGetId($user);

        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    public function updateData (Array $user, $id)
    {
        $result = DB::table($this->userTable)
            ->where('id', '=', $id)
            ->update($user);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData ($id, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->userTable)
                ->where('id', $id)
                ->delete();
        } else {
            $result = DB::table($this->userTable)
                ->where('id', $id)
                ->update(['status' => 2]);
        }

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
}