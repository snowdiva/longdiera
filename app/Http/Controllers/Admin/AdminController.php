<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Repositories\AuthRepo;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends BaseController
{
    public function __construct()
    {
        // TODO::无
    }

    public function showMenu($authRepo)
    {
        if (Session::has('admin_menu')) {
            return Session::get('admin_menu');
        }
        $user = Session::get('user');

        if (2 == $user['group_id']) {
            $authList = $authRepo->getAll();
        } else {
            $authList = $authRepo->getList($user['uid']);
        }

        Session::put('admin_menu', $authList);

        return $authList;
    }

    public function toError($message = '操作失败', $url = '')
    {
//        if (Request::ajax()) {
//            return Response::json(['status' => 422, 'message' => $message, 'url' => $url])
//                ->withCallback(Request::input('callback'));
//        } else {
            return view('admin.error', ['message' => $message, 'url' => $url]);
//        }
    }

    public function toSuccess($message = '操作成功', $url = '')
    {
//        if ($request->ajax()) {
//            return Response::json(['status' => 200, 'message' => $message, 'url' => $url])
//                ->withCallback(Request::input('callback'));
//        } else {
            return view('admin.success', ['message' => $message, 'url' => $url]);
//        }
    }

    public function toJsonError($msg = '操作失败', $code = 400)
    {
        return $this->toJson([
            'error' => $code,
            'message' => $msg
        ]);
    }

    public function toJsonSucess($data = [], $msg = '操作成功')
    {
        return $this->toJson([
            'error' => 0,
            'data' => $data,
            'msg' => $msg
        ]);
    }

    public function toJson($arr)
    {
        return json_encode($arr);
    }
}