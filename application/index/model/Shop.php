<?php
namespace app\index\model;

use think\Model;

class Shop extends Model
{
	//返回数组集对象名
	protected $resultSetType="collection";
	//开启时间戳
	protected $autoWriteTimestamp = true;
	// 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
	
}