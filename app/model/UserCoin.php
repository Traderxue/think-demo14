<?php
namespace app\model;

use think\Model;
use app\model\Balance as BalanceModel;


class UserCoin extends Model
{
    protected $table = "user_coin";

    public function freeze($user_coin)
    {
        $this->startTrans();
        try {
            //code...
            $balance = BalanceModel::where("user_id", $user_coin->u_id)->find();
            $balance->save([
                "can_use" => (float) $balance->can_use - (float) $user_coin->num,
                "freeze" => (float) $balance->freeze + (float) $user_coin->num
            ]);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
        }

    }

    //定时任务，定时结算
    public function finish($user_coin)
    {       //结算
        $this->startTrans();
        try {
            $balance = BalanceModel::where("uid", $user_coin->u_id)->find();
            $balance->save([
                "all_money" => (float) $balance->all_money + (float) $user_coin->expected_income,
                "can_use" => (float) $balance->can_use + (float) $user_coin->expected_income + (float) $user_coin->num,
                "freeze"=>(float) $balance->freeze - (float) $user_coin->num,
            ]);

            $user_coin->save([
                "status"=>1
            ]);

            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
        }
    }
}