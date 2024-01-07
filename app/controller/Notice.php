<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Notice as NoticeModel;
use app\util\Res;
use app\util\Upload;

class Notice extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->rueslt = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();
        $notice = new NoticeModel([
            "title" => $post["title"],
            "content" => $post["content"]
        ]);
        if ($notice->save()) {
            return $this->result->success("添加数据成功", $notice);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request)
    {
        $post = $request->post();

        $notice = NoticeModel::where("id", $post["id"])->find();

        $res = $notice->save([
            "title" => $post["title"],
            "content" => $post["content"]
        ]);
        if ($res) {
            return $this->result->success("编辑数据成功", $res);
        }
        return $this->result->error("编辑数据失败");
    }

    public function deleteById($id)
    {
        $res = NoticeModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }

    public function getAll()
    {
        $list = NoticeModel::all();
        return $this->result->success("获取数据成功", $list);
    }
}