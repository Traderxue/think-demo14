<?php
namespace app\model;

use think\Model;
use app\model\Promoter;

class User extends Model
{
    protected $table = "user";

    public function getByUsername($username)
    {
        return $this->where("username", $username)->find();
    }

    public function getById($id)
    {
        return $this->where("id", $id)->find();
    }

    public function getByInviteCode($code)
    {
        return $this->where("invite_code", $code)->find();
    }

    public function invite($promoter_id, $refered_user_id)
    {
        $promoter = new Promoter();
        $promoter->save([
            "promoter_id" => $promoter_id,
            "refered_user_id" => $refered_user_id,
            "add_time" => date("Y-m-d H:i:s"),
            "reword_amount" => 20
        ]);
    }
}