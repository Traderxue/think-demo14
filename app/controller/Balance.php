<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\User as UserModel;
use app\model\Balance as BalanceModel;
use app\util\Res;

class Balance extends BaseController
{
    private $result;

    function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);

        $list = BalanceModel::paginate([
            "page" => $page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success('获取数据成功',$list);
    }

    function get($uid){
        $balance = BalanceModel::where('uid',$uid)->find();
        return $this->result->success("获取数据成功",$balance);
    }
}