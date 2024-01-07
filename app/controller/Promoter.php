<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Promoter as PromoterModel;
use app\util\Res;

class Promoter extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function getByUid($uid){
        $list = PromoterModel::where("promoter_id",$uid)->select();
        return $this->result->success("获取数据成功",$list);
    }

    public function verify($id){
        $pro = PromoterModel::where("id",$id)->find();
        $res = $pro->save([
            "reword_status"=>1
        ]);
        if($res){
            $promoter = new PromoterModel();
            $promoter->reword($pro->promoter_id);
            return $this->result->success("奖励已发放",$res);
        }
        return $this->result->error("操作失败");
    }

    public function page(Request $request){
        $page = $request->param('page',1);
        $pageSize = $request->param("pageSize",10);

        $list= PromoterModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success('获取数据成功',$list);
    }

}