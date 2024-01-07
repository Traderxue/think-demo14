<?php
namespace app\model;

use think\Model;
use app\model\Balance;
use app\model\Funds;

class Promoter extends Model
{
    protected $table = "promoter";

    public function reword($uid)
    {
        $this->startTrans();
        try {
            $balance = Balance::where("uid", $uid)->find();
            $funds = new Funds();

            $funds->save([
                "uid" => $uid,
                "operate" => "邀请奖励",
                "num" => 20,
                "status" => 1,
                "add_time" => date("Y-m-d H:i:s")
            ]);
            $balance->save([
                "can_use" => (float) $balance->can_use + 20,
                "all_money" => (float) $balance->can_use + (float) $balance->freeze
            ]);
            // 提交事务
            $this->commit();
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            throw $e;
        }
    }
}