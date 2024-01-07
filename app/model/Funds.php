<?php
namespace app\model;

use think\Model;
use app\model\Balance;

class Funds extends Model{
    protected $table = "funds";

    public function verifyBalance($funds){
        $this->startTrans();
        try {
        $balance = Balance::where("uid",$funds->uid)->find();   
        if($funds->operate == "提现"){
            $balance->save([
                "can_user"=>(float) $balance->can_user - (float) $funds->num,
                "all_money"=>(float) $balance->all_money - (float) $funds->num,
            ]);
        }else{
            $balance->save([
                "can_user"=>(float) $balance->can_user + (float) $funds->num,
                "all_money"=>(float) $balance->all_money + (float) $funds->num,
            ]);
        }
        $this->commit();
        } catch (\Throwable $th) {
            //throw $th;
            $this->rollback();
        }
    }
}