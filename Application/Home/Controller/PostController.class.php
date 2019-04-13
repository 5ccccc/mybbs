<?php

namespace Home\Controller;

use Think\Controller;

class PostController extends Controller
{
	//发帖-显示表单
	public function create()
	{
		//可能接接收到一个板块信息
		$cid = empty($_GET['cid']) ? 0 : $_GET['cid'];

		//判断是否登入,没有登入的话跳转到登入去
		if (empty($_SESSION['flag'])) {
			$this->error('请先登入...','/');
		}

		//获取版块信息
		$cates = M('bbs_cate')->getField('cid,cname');

        $this->assign('cid',$cid);
        $this->assign('cates',$cates);
		$this->display(); //View/Post/create.html
	}

	//发帖-接收保存
	public function save()
	{
		$data = $_POST; //获取帖子内容

		//发帖人
		$data['uid'] = $_SESSION['userInfo']['uid'];
		 

		//创建时间,更新时间
		$data['updated_at'] = $data['created_at'] = time();
		$row = M('bbs_post')->add( $data );

		if ($row) {
			$this->success('帖子发布成功');
		} else {
			$this->error('帖子发表失败');
		}
	}

	public function index()
	{
		//要显示哪个板块下面的帖子
        $cid = empty($_GET['cid']) ? 1 : $_GET['cid'];

		//获取数据
		$posts = M('bbs_post')->where("cid=$cid")->order("updated_at desc")->select();

		//获取所有用户信息
		$users = M('bbs_user')->getField('uid,uname');

		//遍历显示
		$this->assign('posts',$posts);
		$this->assign('users',$users);
		$this->display();  //View/Post/index.html
	}
}
?>