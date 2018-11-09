<?php
namespace app\admin\validate;
use think\Validate;
class Shop extends Validate
{
	// 定义验证规则
	protected $rule = [
		'shopname' => 'require',
		'num' => 'require|number',
		'price' => 'require|number',
		'address'=>'require',
		'des'=>'require',
		'typeid'=>'require'
	];
	// 定义错误输出信息
	protected $message = [
		'shopname.require' => '请填写商品名',
		'num.require' => '请填写库存',
		'num.number' => '库存必须是整数',
		'price.require' => '请填写价格',
		'price.number' => '价格必须是数字',
		'address.require' => '请填写商品产地',
		'des.require' => '请填写商品介绍',
		'typeid.require' => '请选择商品类别',
	];

	// 定义验证场景
	protected $scene = [
		'update' => 'Aid,name,age'
	];

}
?>