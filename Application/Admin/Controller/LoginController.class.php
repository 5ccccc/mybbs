<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function login()
    {
    	$this->display();    //View/Login/login.html
    }

    //接收登录页面账号和密码等信息,进行验证
    public function dologin()
    {
    	$uname = $_POST['uname'];
    	$upwd  = $_POST['upwd'];
        $code  = $_POST['code'];

        //验证码检测
        $verify = new \Think\Verify();
        if ( !$verify->check($code) ) {
            $this->error('验证码不对!');
        }

    	//有此账号放回数组,没有就放回NULL
    	$user = M('bbs_user')->where("uname='$uname'")->find();

   		if ( $user && password_verify($upwd, $user['upwd'])) {

   			//保存当前登入成功用户信息
   			$_SESSION['userInfo'] = $user;

   			//是否登录 true登入成功
   			$_SESSION['flag'] = true;

   			$this->success('登入成功','/index.php?m=admin&c=index&a=index');
   		} else {
   			$this->error('账号或者密码不对!');
   		}
   	}

   	//退出
   	public function logout()
   	{
   		$_SESSION['userInfo'] = NULL;
   		$_SESSION['flag'] = false;

   		$this->success('成功退出....','/index.php?m=admin&c=login&a=login');
   	}

    //生成验证码
    public function code()
    {
        $config = array(
                'fontSize' => 20,    //验证码字体大小
                'length'   => 3,     //验证码位数
                'useNoise' => false, //关闭验证码杂点
                'useCurve' => false,
                'imageW'   => 120,
                'imageH'   => 40,
        );

        $Verify = new \Think\Verify( $config );
        $Verify->entry();
    }
}