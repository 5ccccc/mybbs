<?php
namespace Admin\Controller;

use Think\Controller;

class PartController extends CommonController
{
    //添加分区-表单
    public function create()
    {
   		$this->display();  //显示表单 View/Part/create.html
    }

    //添加分区-接收数据保存
    public function save()
    {
   		$row = M('bbs_part')->add($_POST);  //在bbs_part数据库里添加一条记录,并得到受影响行数$row
   
   		if ($row) {
   			$this->success('添加分区成功');
   		} else {
   			$this->error('添加分区失败');
   		}
    }

    //查看所有分区
    public function index()
    {
   		//获取数据
   		$parts = M('bbs_part')->select();

   		//遍历显示
   		$this->assign('parts',$parts);  
   		$this->display();  //View/Part/index.html 分配显示到页面
    }

    //删除分区
    public function del()
    {
   		$pid = $_GET['pid'];   //接收删除id号
   		$row = M('bbs_part')->delete( $pid ); 
   		if ($row) {
   			$this->success('删除分区成功');
   		} else {
   			$this->error('删除分区失败');
   		}
    }

    //修改分区-显示原有数据
    public function edit()
    {
   	    //显示原来的数据
   		$pid = $_GET['pid'];
   		$part = M('bbs_part')->find( $pid );//在数据库中找到原有数据$part

   		//分配显示到页面或分区
   		$this->assign('part',$part);
   		$this->display();   //View/Part/edit.html
    }

    //修改分区-接收修改后的数据,更新
    public function update()
    {
   		$pid = $_GET['pid'];
   		
   		$row = M('bbs_part')->where("pid=$pid")->save( $_POST );
   		if ($row) {
   			$this->success('修改分区成功');
   		} else {
   			$this->error('修改分区失败');
   		}
   	
    }
}