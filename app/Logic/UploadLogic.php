<?php

namespace App\Logic;

use Illuminate\Support\Facades\File;

class UploadLogic
{
    public function cover($file, $novelId)
    {
        if (!$file->isValid()) {
            return $this->toError('上传失败,请联系管理员');
        }

        $realPath = $file->getRealPath();    //这个表示的是缓存在tmp文件夹下的文件的绝对路径
        $entension = $file->getClientOriginalExtension();   //上传文件的后缀.

        $newPath = public_path() . '/storage/cover/' . floor($novelId / 1000);
        if (!is_dir($newPath)) {
            File::makeDirectory($newPath, 0777, true, true);
        }

        $copyToPath = $newPath . '/' . $novelId . '.' . $entension;

        if (!File::move($realPath, $copyToPath)) {
            return $this->error('上传失败,请联系管理员');
        }

        File::chmod($copyToPath, 0777);

        return true;
    }
}