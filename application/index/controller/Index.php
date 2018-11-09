<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Shop;
use think\Db;
use think\Erquest;
use app\index\model\Vip;
use app\index\model\Address;
use think\Session;
use think\Validate;
use think\Request;
class Index extends Controller
{
	//验证码
	public function code(){
		$phone=input('post.phone');
		$code = rand(100000-999999);
//		$host = "https://fesms.market.alicloudapi.com";//api访问链接
//	    $path = "/sms/";//API访问后缀
//	    $method = "GET";
//	    $appcode = "d94aa599e48d47a78d0eb93c2cd6a68f";//替换成自己的阿里云appcode
//	    $headers = array();
//	    array_push($headers, "Authorization:APPCODE " . $appcode);
//	    $querys = "code=".$code."&phone=".$phone."&skin=1&sign=1";  //参数写在这里, 自定义skin编号请找客服申请
//	    $bodys = "";
//	    $url = $host . $path . "?" . $querys;//url拼接
//	
//	    $curl = curl_init();
//	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//	    curl_setopt($curl, CURLOPT_URL, $url);
//	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
//	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//	    curl_setopt($curl, CURLOPT_HEADER, false);
//	    //curl_setopt($curl, CURLOPT_HEADER, true); 如不输出json, 请打开这行代码，打印调试头部状态码。
//	    //状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
//	    if (1 == strpos("$".$host, "https://"))
//	    {
//	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//	    }
//	    $out_put = curl_exec($curl);
//	    echo $out_put;
		echo $phone;
	}
	//退出登录,登陆状态
	public function state(){
		Session::clear();
		echo "退出成功";
	}
	
	//首页
    public function index()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	
    	return $this->fetch();    
    }
    
    //注册
    public function log()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	return $this->fetch();
    }
    //注册添加
    public function con(){
    	$result = $this->validate(input('post.'),'Vali.add');
    	if($result !== true){
    		$this->error($result,'index/log');
    	}else{
    		$vip = new Vip();
    		$vip->allowField(true)->save(input('post.'));
    		Session::set('name',input('post.name'));
    		echo $vip->id;
    	}
    	
    }
    
    //注册中查询账号是否重复
    public function find(){
    	
    	$vip = new Vip();
    	$name = input('post.name');
    	$res = $vip->where('name',$name)->select();
    	echo $res->count();
    }
    
    //登陆验证账号密码是否正确
    public function goyz(){
    	$vip = new Vip();
    	$name=input('post.name');
    	$pass=md5(input('post.pass'));
    	$res = $vip->where('name',$name)->find();
    	if($res->password==$pass){
    		Session::set('name',$name);
    		echo 1;
    	}else{
    		echo 2;
    	}
    }
    
    //登陆账户
    public function login()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	return $this->fetch();
    }
    
    //修改个人资料
    public function edit_data(){
    	$file = request()->file('file');
    	if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            //echo $info->getSaveName();
	            $vip = new Vip();
	            $name = Session::get('name');
	            $find = $vip->where('name',$name)->find();
	            $oldimg = $find->imgname;
	            $resu = $vip->save(['imgname'=>$info->getSaveName()],['name'=>$name]);
	            if($resu){
	            	//删除原来图片
	            	if($oldimg != 'touxiang.jpg'){
	            		$resu1 = unlink(ROOT_PATH . 'public' . DS . 'uploads/'.$oldimg);
		            	if($resu1){
			            	$res = $vip->allowField(true)->save(input('post.'),['name'=>$name]);
			            	$v=['imgname'=>$info->getSaveName()];
			            	$this->assign('v',$v);
			            	echo "修改成功";
			            }
	            	}else{
		            	$res = $vip->allowField(true)->save(input('post.'),['name'=>$name]);
		            	echo "修改成功";
	            	}
	            }
	            
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }
	    }else{
	    	$vip = new Vip();
	    	$name = Session::get('name');
	    	$res = $vip->allowField(true)->save(input('post.'),['name'=>$name]);
	    	echo "修改成功";
	    }
	}
    
    //修改密码
    public function edit(){
    	$name = Session::get('name');
    	$oldpass = md5(input('post.oldpass'));
    	$vip = new Vip();
    	$res = $vip->where('name',$name)->find();
    	if($oldpass == $res->password){
    		$result = $this->validate(input('post.'),'Vali.edit');
    		if($result !== true){
    			$this->error($result);
    		}else{
    			$resu = $vip->where('name',$name)->update(['password' => md5(input('post.password'))]);
    			$this->success('修改成功');
    		}
    	}else{
    		$this->error('原密码错误');
    	}
    }
    
    //商品列表页
    public function catalog_list()
    {	
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	//接受搜索内容
		$price=input('post.price');
		$search=input('get.search');
		if(isset($price)){
			//判断是否价格,销量排序
			$arr = Db::table('shop')->where('type|shopname|des','like','%'.$search.'%')->order("$price")->paginate(6);
			$page=$arr->render();
			return $arr;
		}else{
			$arr = Db::table('shop')->where('type|shopname|des','like','%'.$search.'%')->paginate(6);
			$page=$arr->render();
		}
		$this->assign('page',$page);
		$this->assign('center',$search);
    	$this->assign('arr',$arr);
    	$this->assign('title',$search);
    	return $this->fetch();
    }
    
    //商品详情页
    public function product_page()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	$id = input('get.id');
    	$shop = new Shop();
    	$arr = $shop->where('id',$id)->select();
    	$this->assign('arr',$arr);
    	return $this->fetch();
    }
    
    //我的账户页面
    public function center(){
    	
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		//查询账户个人资料
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    		$this->assign('title','我的账户');
    		$vip = new Vip();
    		$res = $vip->where('name',$name)->select();
    		$this->assign('arr',$res);
    		//查询收货地址
    		$address = new Address();
    		$resu = $address->where('name',$name)->paginate(5);
    		$this->assign('address',$resu);
    		return $this->fetch();
    	}else{
    		//没有登陆的设置登录
    		$this->redirect('index/login');
    	}
    }
    
    //添加收货地址
    public function add_address(){
    	$address = new Address();
    	$name = Session::get('name');
    	$res=$address->allowField(true)->save(['name' => $name,'username' => input('post.username'),'phone' => input('post.phone1'),'address' => input('post.address')]);
    	echo $address->id;
    }
    
    //购物车
    public function shopping_cart()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	return $this->fetch();
    }
    
    //前往结账
    public function checkout()
    {
    	//判断有没有登陆
    	$name = Session::get('name');
    	if($name){
    		$text="<a href=''>你好 ,".$name."</a>   <a href='' style='color:#333;' id='exit' onclick='fun()'>退出</a>";
    		$this->assign('user',$text);
    	}else{
    		$text='欢迎访客，您可以<a href="/index/index/login">登陆</a> 或者 <a href="/index/index/log">注册账户</a>.';
    		$this->assign('user',$text);
    	}
    	return $this->fetch();
    }
}
