<?php
namespace app\index\validate;

use think\Validate;

class Vali extends Validate
{
    protected $rule = [
        'name'  =>  'require|length:6,12|alphaDash',
        'phone' =>  'require|number',
        'password' => 'require|confirm',
    ];
    protected $message = [
    	'name.require' => '账号不能为空',
    	'name.length' => '账户长度为6-12位',
    	'name.alphaDash' => '账户必须为字母 数字 下划线',
    	'phone.require' => '手机号必须填写',
    	'phone.number' => '手机号必须为数字',
    	'password.require' => '密码不能为空',
    	'password.confirm' => '两次密码不一致',
    ];
    
    protected $scene = [
        'add'   =>  ['name','phone'],
        'edit'  =>  ['password'],
    ];

}
?>