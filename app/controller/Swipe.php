<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Swipe as SwipeModel;
use app\util\Res;
use app\util\Upload;

class Swipe extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new SwipeModel();
    }

    public function add(Request $request){
        $upload = new Upload();

        $url = $upload->file();

        $swipe = new SwipeModel([
            "url"=>$url
        ]);
        $res = $swipe->save();

        if($res){
            return $this->result->success("获取数据成功",$res);
        }
        return $this->result->error("获取数据失败");
    }

    public function delete($id){
        $res = SwipeModel::destroy($id);
        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

    public function get(){
        $list =  SwipeModel::select();
        return $this->result->success("获取数据成功",$list);
    }


}