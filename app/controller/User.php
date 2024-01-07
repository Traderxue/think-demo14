<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\User as UserModel;
use app\util\Res;
use app\util\Upload;
use Firebase\JWT\JWT;

class User extends BaseController
{
    private $result;

    private $userModel;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
        $this->userModel = new UserModel();
    }

    public function register(Request $request)
    {
        $post = $request->post();

        if ($this->userModel->getByUsername($post["username"])) {
            return $this->result->error('用户已存在');
        }

        $res = $this->userModel->save([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT),
            "ip" => $request->ip(),
            "add_time" => date("Y-m-d H:i:s")
        ]);

        if (!$res) {
            return $this->result->error("注册失败");
        }
        $refer = $this->userModel->getByUsername($post["username"]);
        if ($post["invite_code"]) {
            //操作promote表添加一条记录
            $promoter = $this->userModel->getByInviteCode($post["invite_code"]);

            $this->userModel->invite($promoter->id, $refer->id);
        }
        return $this->result->success("注册成功", $refer);
    }

    public function login(Request $request)
    {
        $post = $request->post();

        $user = $this->userModel->getByUsername($post["username"]);

        if (!$user) {
            return $this->result->error("用户不存在");
        }

        if (password_verify($post["password"], $user->password)) {
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" => time(),  // token 的生效时间单位s
                "exp" => time() + 60 * 60 * 24,  // token 的过期时间 24H
                "data" => [
                    // 包含的用户信息等数据
                    "username" => $user->username,
                ]
            );
            // 使用密钥进行签名
            $token = JWT::encode($payload, $secretKey, 'HS256');
            return $this->result->success("登录成功", [
                "token"=>$token,
                "user"=>$user
            ]);
        }
        return $this->result->error("登录失败");
    }

    public function edit(Request $request){
        $post = $request->post();

        $user = $this->userModel->getById($post["id"]);

        $upload = new Upload();
        $url = $upload->file();

        $res = $user->save([
            "email"=>$post["email"],
            "nickname"=>$post["nickname"],
            "avator"=>$url,
            "invite_code"=>$post["invite_code"]
        ]);
        if($res){
            return $this->result->success("编辑数据成功",$res);
        }
        return $this->result->error("编辑数据失败");
    }

    public function disabled($id){
        $user = $this->userModel->getById($id);

        $res = $user->save(["disabled"=>1]);
        if($res){
            return $this->result->success('禁用成功',$res);
        }
        return $this->result->error('禁用失败');
    }

    public function enabled($id){
        $user = $this->userModel->getById($id);

        $res = $user->save(["disabled"=>0]);

        if($res){
            return $this->result->success("启用成功",$res);
        }
        return $this->result->error("启用失败");
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $keyword = $request->param("keyword");

        $list = UserModel::where("username","like","%{$keyword}%")->whereOr("nickname","like","%{$keyword}%")
            ->paginate([
                "page"=>$page,
                "list_rows"=>$pageSize
            ]);

        return $this->result->success("获取数据成功",$list);
    }
}