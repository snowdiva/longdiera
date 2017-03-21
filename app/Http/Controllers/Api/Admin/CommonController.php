<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    protected $themeName = 'default.';

    /**
     * 返回错误的Json信息
     * @param $message 错误提示信息
     * @param string $errorCode 错误编码
     * @param array $errors 错误信息详情
     * @return mixed
     */
    public function error($message, $errorCode = '400', $errors = [])
    {
        return response()->json([
            'error' => intval($errorCode),
            'message' => $message,
            'errors' => $errors
        ]);
    }

    /**
     * 返回成功的Json信息
     * @param $message 成功提示信息
     * @return mixed
     */
    public function success($message)
    {
        return response()->json([
            'error' => 0,
            'message' => $message
        ]);
    }

    /**
     * 返回Json格式数据
     * @param $data 返回数据
     * @return mixed
     */
    public function returnJson($data)
    {
        return response()->json([
            'error' => 0,
            'data' => $data
        ]);
    }
}