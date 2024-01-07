<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Setting as SettingModel;
use app\util\Res;
use app\util\Upload;

class Setting extends BaseController{
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function edit(Request $request){
        $post = $request->post();

        $upload = new Upload();

        $url = $upload->file();

        $setting = new SettingModel([
            "name"=>$post["name"],
            "introduce"=>$post["introduce"],
            "logo"=>$url
        ]);

        $res = $setting->save();
        if($res){
            return $this->result->success("编辑数据成功",$res);
        }
        return $this->result->error("编辑数据失败");
     }
}