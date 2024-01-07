<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Funds as FundsModel;
use app\util\Res;

//资金记录
class Funds extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();        
    }

    public function getByUid($uid){
        $funds = FundsModel::where("uid",$uid)->select();
        return $this->result->success("获取数据成功",$funds);
    }

    public function add(Request $request){
        $post = $request->post();

        $funds = new FundsModel([
            "uid"=>$post["uid"],
            "operate"=>$post["operate"],
            "num"=>$post["num"],
            "add_time"=>date("Y-m-d H:i:s")
        ]);

        $res = $funds->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        
        $list = FundsModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("获取数据成功",$list);
    }

    public function verify($id){
        $funds = FundsModel::where("id",$id)->find();

        $res = $funds->save([
            "status"=>1
        ]);
        if($res){
            $fund = new FundsModel();
            $fund->verifyBalance($funds);
            return $this->result->success("审核通过",$res);
        }
        return $this->result->error("审核失败");
    }
    
}
