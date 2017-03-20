<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AuthRepo;
use Illuminate\Http\Request;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class UserController extends AdminController
{
    public function login(Request $request)
    {
        // TODO::根据请求URL地址进行登录之后的跳转

        if ($request->session()->exists('user')) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.login');
        }
    }

    public function loginPost(Request $request, UserRepo $userRepo, Response $response, AuthRepo $authRepo)
    {
        if (!Session::has('user')) redirect()->route('dashboard');

        $userName = $request->input('user_name');
        $password = $request->input('password');
        $week_login = $request->input('week_login', 0);

        $userInfo = $userRepo->checkUser($userName, $password);
        if (!$userInfo) return $this->toError('用户名或密码错误');
        if (0 == $userInfo->status) return $this->toError('该账号已被锁定,请联系管理员');

        $user = [
            'user_id' => $userInfo->id,
            'username' => $userInfo->username,
            'alias' => $userInfo->alias,
            'group_id' => $userInfo->group_id,
            'gender' => $userInfo->gender
        ];

//        if (1 == $user['user_id']) {
//            $user['admin_menu'] = $authRepo->getAll();
//        } else {
//            $user['admin_menu'] = $authRepo->getList($user['user_id']);
//        }

//        $user['admin_auth'] = $authRepo->getAuthority($user['user_id']);

        Session::put('user', $user);

        if (1 == $week_login) $response->withCookie(cookie(Session::getName(), Session::getId(), time()+604800));

        return redirect()->route('dashboard');
    }

    /**
     * 注销登录用户
     * @param Request $request
     * @param UserRepo $userRepo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request, UserRepo $userRepo)
    {
        if (!$request->session()->exists('user')) return redirect()->route('adminLogin');

        Session::flush();

        return redirect()->route('adminLogin');
    }

    /**
     * 后台用户列表
     * @param Request $request
     * @param UserRepo $userRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, UserRepo $userRepo)
    {
        $where = [];
        $searchUid = $request->input('search_user_id', 0);
        $searchUname = $request->input('search_uname', '');

        if ($searchUid > 0) {
            $where['id'] = $searchUid;
        } else {
            if ('' != $searchUname) $where['username'] = $searchUname;
        }

        $list = $userRepo->getData($where);

        return view('admin.users', ['list' => $list]);
    }

    public function getInfo(Request $request, UserRepo $userRepo)
    {
        // 首先从session中检索信息
        if ($request->session()->exists('user')) return $request->session()->get('user');

        // 验证登录信息
        $username = $request->input('username', '');
        $password = $request->input('password', 'md5');

        return $this->toJson(['username' => $username, 'password' => $password, 'message' => 'success']);
    }
}