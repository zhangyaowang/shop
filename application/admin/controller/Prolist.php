<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Validate;

class Prolist extends Controller
{
    public function prolist()
    {
    	return $this->fetch('');
    }
    public function probrand()
    {
    	return $this->fetch('');
    }
    
    public function procategory()
    {
    	//查询type表中的所有信息
	  	$arr=Db::table("type")->field("id,name,pid,path,concat(path,'-',id) as bpath")->order('bpath')->select();
    	foreach($arr as $k){
    		$m=substr_count($k['path'],",");	
    		$nbsp=str_repeat("-",$m*4);
    		$res[]=$nbsp.$k['name'];
    	}
    			$this->assign("res",$res);
    	return $this->fetch('');
    }
    
    public function proadd()
    {
    	//商品添加里的类型
    	$arr=Db::table("type")->field("id,name,pid,path,concat(path,'-',id) as bpath")->order('bpath')->select();
    	foreach($arr as $k){
    		$m=substr_count($k['path'],",");	
    		$nbsp=str_repeat("-",$m*4);
    		$res[]=$nbsp.$k['name'];
    	}
		$this->assign("res",$res);
    	return $this->fetch();
    }
    public function shopadd()
    {
    	//商品添加
    	// 验证器
        $validate = validate('Shop');
        if (!$validate->check(input("post."))) {
            $this->error($validate->getError());
        }else{
        	$arr=input('post.');
        	$type=$arr['typeid'];
        	$tname=ltrim($type,"-");
        	//通过类型名查询类型获取id
        	$array=Db::table("type")->where("name",$tname)->select();
        	$tid=$array[0]['id'];
        	$shopname=$arr['shopname'];
        	$num=$arr['num'];
        	$price=$arr['price'];
        	$address=$arr['address'];
        	$des=$arr['des'];
//      	dump($arr);
//      	die;
        }
        	// 获取表单上传的缩略图
            $file = request()->file('picname');
            if($file){
                $info = $file->validate(['size'=>1024*1024*2,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 执行缩略，打开上传的图片
                    $image = \think\Image::open($file);
                    // 拆分文件名，拼接缩略图文件名
                    $arr = explode('\\', $info->getSaveName());
                    $simg = $arr[0] . DS . 's' . $arr[1];
                    // 缩略图移动到上传目录
                    $image->thumb(50, 50)->save(ROOT_PATH . 'public' . DS . 'uploads' . DS . $simg);
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
                $data = [
					['shopname'=>$shopname,'picname'=>$simg,'num'=>$num,'price'=>$price,'typeid'=>$tid,'address'=>$address,'des'=>$des]
					];
				$row=Db::name('shop')->insertAll($data);
                //执行添加
//              $row=Db::table('shop')
//					->shop(['shopname'=>'$shopname','picname'=>'$simg','num'=>'$num','price'=>'$price','typeid'=>'$tid','address'=>'$address','des'=>'$des'])
//					->insert();
				if($row){
					$this->success("添加成功","prolist/prolist","",1);
				}else{
					$this->error("添加失败");
				}
		}
    	return $this->fetch('prolist/prolist');
    }
    public function edit()
    {
    	$str = input('get.title');
		$title=ltrim($str,"-");
		$arr=Db::table("type")->where("name",$title)->select();
		$id=$arr[0]['id'];
		$this->assign("id",$id);
		$this->assign("title",$title);
    	return $this->fetch('');
    }
    public function codeing()
    {
    	return $this->fetch('');
    }
    public function procateadd()
    {
    	//接收procategory传过来的分类名称
    	$str = input('get.title');
		$title=ltrim($str,"-");  	
    	//从数据表中进行条件查询
    	$arr=Db::table("type")->where("name",$title)->select();
    	//遍历
    	if(count($arr)==0){
    		$pid=0;
    		$path="0,";
    		$this->assign("pid",$pid);
    		$this->assign("path",$path);
    	}else{
	    	foreach($arr as $k){
	    		$num=substr_count($k['path'],",")+1;
	    		$m=$num."级";
	    		$tit=$title."下的";
	    		$pid=$k['id'];
	    		$path=$k['path'].$k['id'].",";
	    		$this->assign("pid",$pid);
	    		$this->assign("path",$path);
	    		$this->assign("m",$m);
	    		$this->assign('tit',$tit);   		
	    	}
    	}
    	    	
    	return $this->fetch('');
    }
    
    public function procateinsert()
    {
    	//接收表单值
  		$res=input("post.");
  		//判断数据库里是否存在接收到的名字
  		$arr=Db::table("type")->where("name",$res['name'])->select();
  		if($arr[0]['name']==$res['name']){
  			$this->error("请勿添加重复的类别","prolist/procateadd","",1);
  		}
    	//判断值是否为空
    	if(input("post.name")==null){
    		$this->error("类别名称不能为空","prolist/procateadd","",1);
    	}else{
    		$result=Db::table("type")->insert($res);
    		if($result){
    			$this->success("添加成功","prolist/procateadd","",1);
    		}else{
    			$this->error("添加失败","prolist/procateadd","",1);
    		}
    	}   	
    	return $this->fetch("prolist/procateadd");
    }
    public function update()
    {
    	//接收表单值
  		$res=input("post.");
		//判断数据库里是否存在接收到的名字
		$arr=Db::table("type")->where("name",$res['name'])->select();
		$result=Db::table("type")->where("pid",$res['id'])->select();
		if($res['name']==null){
			$this->error("类别名称不能为空","prolist/edit","",1);
		}
		if($arr[0]['name']==$res['name']){
			$this->error("请勿添加重复的类别","prolist/edit","",1);
		}
		
		if($result[0]['name']!=null){
			$this->error("类别下有子类，不能修改","prolist/edit","",1);
		}else{
			Db::table('type')->where('id',$res['id'])->update(['name' => $res['name']]);
			$this->success("修改成功","prolist/procateadd","",1);
		}
    	return $this->fetch("prolist/edit");
    }
    public function del()
    {
    	//接收表单值
  		$id=input("get.");
		//判断数据库里是否存在接收到的名字
		$result=Db::table("type")->where("pid",$id['id'])->select();
		if($result[0]['name']!=null){
			$this->error("类别下有子类，不能删除","prolist/edit","",1);
		}else{
			Db::table('type')->where('id',$id['id'])->delete();
			$this->success("删除成功","prolist/procateadd","",1);
		}
    	return $this->fetch("prolist/edit");
    }
}
?>