<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Adlist extends Controller
{
    public function adlist()
    {
    	
    	 
    	$arr=Db::table("user")->select();
    	$this->assign("arr",$arr);
    	// dump($arr);die;
    	$b['0']=["超级管理员","已启用"," label-success"];
    	$b['1']=["管理员","已启用"," label-success"];
    	$b['2']=["管理员","已停用",""];
    	$this->assign("b",$b);
    	// foreach($arr as $k=>$v){
    	// 	$this->assign("v",$v);
    	// }
    	return $this->fetch();
    }

}
?>