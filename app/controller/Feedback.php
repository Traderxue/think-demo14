<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Feedback as FeedbackModel;
use app\util\Res;
use app\util\Upload;

class Feedback extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();

        $feed = new FeedbackModel([
            "u_id"=>$post["u_id"],
            "title"=>$post["title"],
            "content"=>$post["content"]
        ]);

        $res = $feed->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);

        $list = FeedbackModel::page($page,$pageSize);

        return $this->result->success("获取数据成功",$list);
    }

    public function getByUid($u_id){
        $list = FeedbackModel::where("",$u_id)->find();
        return $this->result->success("获取数据成功",$list);
    }
}