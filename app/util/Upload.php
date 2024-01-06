<?php
namespace app\util;

use think\Facade\Request;

class Upload{
    public function file(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 上传到本地服务器
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $file);

        $savename = str_replace("\\","/",$savename);

        return Request::domain().'/storage/'.$savename;
    }
}