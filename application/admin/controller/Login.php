<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{
    public function login()
    {
    	return $this->fetch('');
    }
    public function check()
    {
    	$name = input('post.name');
    	$pass = input('post.pass');
    	$arr=Db::table("user")->where("username",$name)->select();
    	$this->assign("arr",$arr);

    	if ($arr==null) {
    		$this->success('用户不存在',url('login/login'));
    		
    	}else{
    		foreach($arr as $k=>$v){
    			if($v['password']==$pass) {
    				Session::set('user',$name);
					 $this->success('登陆成功',url('admin/index/index'));
    				
    			}else{
    				$this->success('密码不正确',url('login/login'));
    				
				}
    		}
    	}
    }
}

 	
?>