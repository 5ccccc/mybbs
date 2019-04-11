<?php
namespace Admin\Controller;

use Think\Controller;

class CateController extends CommonController
{
    //添加板块
    public function create()
    {
   		//获取所有分区
   		$parts = M('bbs_part')->select();

        //获取用户信息  权限小于3,即超级管理员与管理员
        $users = M('bbs_user')->where("auth<3")->select();

        $this->assign('users',$users);
        $this->assign('parts',$parts);  //把分区分配显示
   		$this->display();  //View/Cate/create.html 
    }
   
    public function save()
    {
        try{
            $row=M('bbs_cate')->add( $_POST );  //添加到数据库 返回受影响行数
        }catch (\Exception $e) {
            $this->error( $e->getMessage() );
        }
     	
        if ($row) {
        	$this->success('添加板块成功');
        } else {
        	$this->error('添加板块失败');
        }
    }

   //查看版块
    public function index()
    {
   		//获取数据
   		$cates = M('bbs_cate')->select();

   		//获取分区信息
   		$parts=M('bbs_part')->select();
   		$parts =array_column($parts, 'pname','pid'); //生成一个pname为值 pid为下标的新数组 形式为:pid=>分区名称  
        /*结果新数组: Array
             (
                [1] => 电影
                [5] => 电视
                [4] => 腾讯体育
                [2] => 音乐酷狗
              )*/

        //获取用户信息
        $users = M('bbs_user')->select();
        $users =array_column($users,'uname','uid');

       /* 还可以用
       	$users =M('bbs_user')->getField('uid,uname');
       */
      
   		//遍历显示数据
   		$this->assign('cates',$cates);
   		$this->assign('parts',$parts);
   		$this->assign('users',$users);
   		$this->display();   //View/Cate/index.html 去创建这个文件
    }
    //删除版块
    public function del()
    {
        $cid = $_GET['cid'];
        $row = M('bbs_cate')->delete($cid);
        if ($row) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    //修改板块-显示原有属性
    public function edit()
    {
        $cid = $_GET['cid'];
        $cate = M('bbs_cate')->find($cid);

        $parts = M('bbs_part')->select();

        //获取用户信息  权限小于3,即超级管理员与管理员
        $users = M('bbs_user')->where("auth<3")->select();

        $this->assign('users',$users);
        $this->assign('parts',$parts);
        $this->assign('cate',$cate);
        $this->display();
    }

    //修改板块-接收修改数据,更新
    public function update()
    {
   	    $cid = $_GET['cid'];

        $row = M('bbs_cate')->where("cid=$cid")->save($_POST);
        if ($row) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
}