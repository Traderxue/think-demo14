<?php
namespace app\controller;

use think\Request;
use app\model\Coin as CoinModel;
use app\model\UserCoin as UserCoinModel;
use app\BaseController;
use app\util\Res;

class Coin extends BaseController{
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){
        $post = $request->post();
        
        $coin = new CoinModel([
            "type"=>$post["type"],
            "cycle"=>$post["cycle"],
            "rate"=>$post["rate"],
            "add_time"=>$post["add_time"]
        ]);        
        $res = $coin->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    function edit(Request $request){
        $post = $request->post();

        $coin = CoinModel::where("id",$post["id"])->find();

        $res = $coin->save([
            "type"=>$post["type"],
            "cycle"=>$post["cycle"],
            "rate"=>$post["rate"],
            "add_time"=>$post["add_time"]
        ]);

        if($res){
            return $this->result->success("编辑数据成功",$coin);
        }
        return $this->result->error('编辑数据失败');
    }

    function getAll(){
        $list = CoinModel::select();
        return $this->result->success("获取数据成功",$list);
    }

    function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);

        $list = CoinModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }

    function deleteById($id){
        $user_coin = UserCoinModel::where("coin_id",$id)->find();

        if($user_coin){
            return $this->result->error("用户正在持有禁止删除");
        }

        $res = CoinModel::destroy($id);

        if($res){
            return $this->result->success("获取数据成功",$res);
        }

        return $this->result->error("获取数据失败");
    }
}