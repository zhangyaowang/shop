<?php
namespace app\index\model;

use think\Model;

class Vip extends Model
{
	// 设置返回数据集的对象名
	protected $resultSetType = 'collection';
	//添加时间戳
	protected $autoWriteTimestamp = true;
	//修改器
	public function setPasswordAttr($value){
		return md5($value);
	}
}
?>