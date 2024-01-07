<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Info as InfoModel;
use app\util\Res;
use app\util\Upload;

class Info extends BaseController{
    private $result;

    public function add(Request $request){
        $post = $request->post();

        $upload = new Upload();

        $url = $upload->file();

        $info = new InfoModel([
            "author"=>$post["author"],
            "title"=>$post["title"],
            "img"=>$url,
            "content"=>$post["content"],
            "add_time"=>date("Y-m-d H:i:s")
        ]);

        $res = $info->save();

        if($res){
            return $this->result->success('添加数据成功',$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request){
        $post = $request->post();

        $info = InfoModel::where("id",$post["id"])->find();

        $upload = new Upload();

        $url = $upload->file();

        $res = $info->save([
            "author"=>$post["author"],
            "title"=>$post["title"],
            "img"=>$url,
            "content"=>$post["content"],
        ]);

        if($res){
            return $this->result->success("编辑数据成功",$res);
        }
        return $this->result->error("编辑数据失败");

    }

    public function deleteById($id){
        $res = InfoModel::where("id",$id)->delete();

        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

    public function getAll(){
        $list = InfoModel::select();
        return $this->result->success("获取数据成功",$list);
    }

    public function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);

        $list = InfoModel::paginate([
            "page"=>$page,
            "pageSize"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }
}
