<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AuthRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AuthorityController extends AdminController
{
    public function authBind(Request $request, AuthRepo $authRepo, UserRepo $userRepo)
    {
        $userId = $request->route('user_id', false);
        if (!$userId) return $this->toError('缺少参数');

        $list = $authRepo->getUserAuthority($userId);
        $user = $userRepo->userInfo($userId);

        return view('admin.auth_bind', [
            'list' => $list,
            'user' => $user
        ]);
    }

    /**
     * 用户绑定权限
     * @param Request $request
     * @param AuthRepo $authRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function authBindPost(Request $request, AuthRepo $authRepo)
    {
        $user_id = $request->input('user_id', 0);
        if (0 == $user_id) return $this->toError('缺少参数');

        $authority_ids = $request->input('authority_ids', '');

        $result = $authRepo->userBindAuth($user_id, $authority_ids);

        if ($result) {
            return redirect()->route('user_index');
        } else {
            return $this->toError('操作失败');
        }
    }

    public function index(AuthRepo $authRepo)
    {
        $user = Session::get('user');

        if (1 == $user['group_id']) {
            $list = $authRepo->getListMenu($user['user_id'], 1);
        } else {
            $list = $authRepo->getListMenu($user['user_id']);
        }

        return view('admin.auth_index', ['list' => $list]);
    }

    public function authAdd(AuthRepo $authRepo)
    {
        $auth = $authRepo->getPidMenu();

        return view('admin.auth_add', ['list' => $auth]);
    }

    public function authAddPost(Request $request, AuthRepo $authRepo)
    {
        $name = $request->input('name', '');
        if ('' == $name) return $this->toError('英文标识不能为空');
        $alias = $request->input('alias', '');
        if ('' == $alias) return $this->toError('中文名称不能为空');
        $url = $request->input('url', '');
        $pid = $request->input('pid', 0);
        if (0 == $pid) {
            // 顶级菜单不允许写入url属性
            $url = '';
        } else {
            // 非顶级菜单必须填写url属性
            if ('' == $url) $this->toError('非顶级菜单必须填写url属性');
        }
        $type = $request->input('type', 2) != 2 ? 1 : 2;
        $description = $request->input('description', '');

        if ($authRepo->addAuth($name, $alias, $url, $description, $type, $pid)) {
            return redirect()->route('auth_index');
        } else {
            return $this->toError('操作失败:可能是[英文标识]或[访问路由]冲突');
        }
    }

    public function authEdit(Request $request, AuthRepo $authRepo)
    {
        $id = $request->route('id', 0);
        if (0 == $id) return $this->toError('缺少必须的参数');

        $list = $authRepo->getOne($id);
        $auth = $authRepo->getPidMenu();

        return view('admin.auth_edit', [
            'list' => $list,
            'auth' => $auth
        ]);
    }
}