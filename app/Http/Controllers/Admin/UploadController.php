<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class UploadController extends AdminController
{
    public function cover(Request $request)
    {
        $file = Input::file('file');

        if (!$file->isValid()) {
            return $this->toError('上传失败,请联系管理员');
        }

        $newName = date('YmdHis') . mt_rand(1,100);

        $realPath = $file->getRealPath();    //这个表示的是缓存在tmp文件夹下的文件的绝对路径
        $entension = $file->getClientOriginalExtension();   //上传文件的后缀.

        $newPath = public_path() . '/storage/images/' . date('Ym');
        if (!is_dir($newPath)) {
            File::makeDirectory($newPath, 0777, true, true);
        }

        $copyToPath = $newPath . '/' . $newName . '.' . $entension;

        if (!File::move($realPath, $copyToPath)) {
            return $this->error('上传失败,请联系管理员');
        }

        File::chmod($copyToPath, 0777);

        return date('Ym') . '/' . $newName . '.' . $entension;
    }

    public function save_image(Request $request)
    {
        dump($request->all());die;
    }

}