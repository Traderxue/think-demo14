<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Admin as AdminModel;
use app\util\Res;
use app\util\Upload;
use Firebase\JWT\JWT;

class Admin extends BaseController{
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){
        $post = $request->post();

        $a = AdminModel::where("username",$post["username"])->find();
        if($a){
            return $this->result->error("用户已存在");
        }

        $admin = new AdminModel([
            "username"=>$post["username"],
            "password"=>password_hash($post["password"],PASSWORD_DEFAULT),
            "add_time"=>date("Y-m-d H:i:s"),
            "ip"=>$request->ip()            
        ]);

        $res = $admin->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    function login(Request $request){
        $post = $request->post();
        
        $admin = AdminModel::where("username",$post["username"])->find();
        if(!$admin){
            return $this->result->error("用户不存在");
        }
        if(password_verify($post["password"],$admin->password)){
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" => time(),  // token 的生效时间单位s
                "exp" => time() + 60 * 60 * 24,  // token 的过期时间 24H
                "data" => [
                    // 包含的用户信息等数据
                    "username" => $admin->username,
                ]
            );
            // 使用密钥进行签名
            $token = JWT::encode($payload, $secretKey, 'HS256');
            return $this->result->success("登录成功", [
                "token"=>$token,
                "user"=>$admin
            ]);
        }
        return $this->result->error("登录失败");
    }

    function edit(Request $request){
        $post = $request->post();

        $admin = AdminModel::where("id",$post["id"])->find();

        $upload = new Upload();

        $url = $upload->file();

        $res = $admin->save([
            "nickname"=>$post["nickname"],
            "avator"=>$post["avator"],
        ]);

        if($res){
            return $this->result->success("编辑数据成功",$res);
        }
        return $this->result->error("编辑数据失败");
    }

    function deleteById($id){
        $res = AdminModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

    function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);
        $keyword = $request->param("keyword");

        $list = AdminModel::where("username","like","{$keyword}")->whereOr("nickname","like","{$keyword}")
            ->paginate([
                "page"=>$page,
                "list_rows"=>$pageSize
            ]);

        return $this->result->success("获取数据成功",$list);
    }
}