<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
    	if(Session::has('user')){
    		$this->assign('title','哈哈');
	    	$this->assign('con1','H-ui.admin v3.1,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载');
	    	return $this->fetch('index'); 
    	}else{
    		return $this->fetch('login/login'); 
    	}
    	
    }
//  public function article()
//  {
//  	return $this->fetch('article-list');
//  }
}
