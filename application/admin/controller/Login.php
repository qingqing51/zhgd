<?php
/**
 * Created by PhpStorm.
 * User: wei1co
 * Date: 2018/10/21
 * Time: 上午9:53
 */

namespace app\admin\controller;


use app\admin\common\Base;
use think\Request;
use app\admin\model\login as LoginModel;
use think\Session;

class Login extends Base
{

    public function login(){
        $this->assign('title','后台管理系统');
        return $this->view->fetch('login');
    }


    public function checkLogin(Request $request){

        $rdata = $request->param();
        $code=0;
        $remsg= "";

        $rule = [
            'username'  => 'require',
            'password'   => 'require',
        ];
        $msg = [
            'username'=>['require'=> '用户名不能为空'] ,
            'password'=>['require'=> '密码不能为空'] ,
        ];

        $remsg = $this->validate($rdata,$rule,$msg);
        if($remsg=== true){

            $map['username']=$rdata['username'];
            $admin = LoginModel::get($map);
            if(is_null($admin)){
                $remsg ="没有找到该用户";
            }else if(md5($rdata['password'])!= $admin['password']){
                $remsg = "用户名或密码不正确";
            }else{
                LoginModel::
                $code=1;
                $remsg="验证通过，点击【确定】继续";

                Session::set('admin_id',$admin->id);
                Session::set('user_info',$admin->getData());
            }

        }

        $result['code']=$code;
        $result['msg']=$remsg;
        $result['rdata']=$rdata;
        return $result;
    }

    public function logout(){
        Session::delete('user_id');
        Session::delete('user_info');
        $this->success('退出登录，正在返回',url('Login/login'));
    }


}