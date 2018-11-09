<?php
namespace app\admin\controller;
use think\Controller;


class Alist extends Controller
{
    public function alist()
    {
    	return $this->fetch('');
    }
}
?>