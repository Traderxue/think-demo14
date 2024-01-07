<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\UserCoin as UserCoinModel;
use app\model\Coin as CoinModel;
use app\util\Res;

class UserCoin  extends BaseController{
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){
        $post = $request->post();

        $coin = CoinModel::where("id",$post["coin_id"])->find();

        $currentDateTime = date("Y-m-d H:i:s");
        $expirationTime = date("Y-m-d H:i:s", strtotime($currentDateTime . " + {$coin->cycle} days"));
        
        $user_coin = new UserCoinModel([
            "uid"=>$post["uid"],
            "coin_id"=>$post["coin_id"],
            "buy_time"=>$currentDateTime,
            "finish_time"=> $expirationTime,
            "num"=>$post["num"],
            "expected_income"=>(float)$post["num"] * (float)$coin->rate
        ]);
    }

    function finish(Request $request){
        
    }
}