<?php

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function __construct() 
    {
        parent::__construct();  //调用父类的构造方法
      
        //验证是否登入成功,若没登入就跳转到登录窗口去
        if ( empty($_SESSION['flag']) ) {
             $this->error('请您先登录','./index.php?m=admin&c=login&a=login');
        }
    }
}
?>