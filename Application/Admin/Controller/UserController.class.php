<?php
namespace Admin\Controller;

use Think\Controller;
use \Think\Image;

class UserController extends CommonController
{				
	//显示表单create 
	public function create()
	{
        //显示
		$this -> display(); 
	}

	//接收表单数据,保存到数据库
	public function save()
	{
		$data = $_POST;

		//添加时间
		$data['created_at'] = time();


		//秘密不能为空
		if (empty($data['upwd']) || empty ( $data['reupwd']) ) {        
			$this->error('密码不能为空');    
		} 
	
		//两次密码要一致
		if ($data['upwd'] !== $data['reupwd']){
						$this-> error('两次密码不一致');
		}
	
		//加密密码
		$data['upwd'] = password_hash($data['upwd'],PASSWORD_DEFAULT);
		//文件上传处理
		$data['uface'] = $this->doUp();

		//拼接缩略图名称
	    /* 
            $thumb_name =$info['uface']['savepath'].'sm_'.$info['uface']['savename'];
        */


		//生成一个缩略图
		$this->doSm();
		//添加到数据库 反回一个受影响行数
		$row = M('bbs_user') -> add($data);
		if ($row) {
			$this->success('添加用户成功!');
		} else {
			$this->error('添加用户失败!');
		}                                                                                            
	}


    //查看index
    public function index()
    {

		//定义一个空数组      index.html的条件
		$condition = [];

		//判断有没有性别条件  index.html的条件
		if (!empty($_GET['sex'])) {
			$condition['sex'] = ['eq',"{$_GET['sex']}"];
		}

		//判断有没有姓名条件  index.html的条件
		if (!empty($_GET['uname'])) {
			$condition['uname'] = ['like',"%{$_GET['uname']}%"];
		}

		//实例化一个表对象，按条件查询得到总记录数     分页时使用
		$User = M('bbs_user');
		$cnt = $User->where( $condition )->count();

		//实例化分页类 传入总记录数和 每页显示的记录数5
		$Page = new \Think\Page($cnt,5);

		//得到分页显示的htnl代码
		$html_page = $Page -> show();


		//获取数据  
		$users = $User->where($condition)              //多条件
		       ->limit($Page->firstRow,$Page->listRows)//分页
			   ->select();                             //按性别姓名条件查询


		/* 遍历方式生成缩略图 跟getSm()函数一样
			foreach($users as $k=>$v){
							$arr = explode('/',$v['uface']);
							$arr[3] = 'sm_'.$arr[3];
							$users[$k]['uface'] = implode('/',$arr);
						}*/

		//显示数据 分配到模版
		$this->assign('users', $users);
		$this->assign('html_page',$html_page); 
		$this->display();
    }  

	//删除指定用户del
    public function del()
	{
		$uid = $_GET['uid'];
		$row = M('bbs_user')->delete( $uid );

		if ($row) {
			$this->success('删除用户成功');
		}else {
			$this->error('删除用户失败');
		}
    }


	//显示原有数据edit
	public function edit()
	{
		$uid= $_GET['uid']; //获取到用户的id
	    $user = M('bbs_user') -> find( $uid );
		/* 
			//显示头像课的代码
			$arr = explode('/',$user['uface']);
			$arr[3] = 'sm_'.$arr[3];
			$user['uface'] = implode('/',$arr);
		*/

		$this -> assign('user',$user);  //分配显示到页面
		$this -> display();							
	}
		
		
	//接收修改后的数据 进行更新update
	public function update()
	{
		$uid = $_GET['uid'];
		$data = $_POST;

		//如果有新的上传头像
		if ($_FILES['uface']['error']!== 4) {    //如果错误号不等于4,(看文件上传的知识)
			$data['uface'] =$this->doUp();       //调用私有成员方法doUp()处理上传文件
			$this->doSm();                       //调用私有成员方法doSm()处理缩略图
			/* unlink('./'.$user->uface);        //删除原来的头像或文件,可以删可以不删*/
		}
				
		$row = M('bbs_user')->where("uid=$uid")->save($data);
		if ($row) {
			$this->success('用户信息修改成功', '/index.php?m=admin&c=user&a=index');
		} else {
			$this->error('用户信息修改失败');
		}
	}
		

	//处理上传文件doUp
	private function doUp()
	{
    	$config = [
			'maxSize' =>3145728,              //文件最大大小
			'rootPath'=>'./',                 //根目录保存路径
			'savePath'=> 'Public/Uploads/',   //保存路径
			'saveName'=> array('uniqid',''),  //保存名称
			'exts'    => array('jpg','gif','png','jpeg'), //类型
			'autoSub' => true,    
			'subName' => array('date','Ymd'),
    	];  
		$up = new \Think\Upload($config);
		$info = $up->upload();                //没有错误发生会返回假
		
		if (!$info) {                         //取反  如果有错输出错误
		    echo ($up->getError());  
		    die;
		}

		//没错的话拼接上传文件的完整名称   并且赋值给成员属性并return
		return $this->filename = $info['uface']['savepath'].$info['uface']['savename'];
	}
		
	//处理 生成缩略图doSm
	private function doSm()
    {
		//生成缩略图代码
		$image = new Image(Image::IMAGE_GD,$this->filename);
		$image->thumb(150,150)->save('./'.getSm($this->filename) );			
	}
						
}
?>