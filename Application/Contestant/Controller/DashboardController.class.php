<?php
namespace Contestant\Controller;
use Think\Controller;
class DashboardController extends Controller {
  public function _before_index(){
    $this->check_login();
    session('recaptcha_passed',null);
  }
  public function index(){
    $ContestantsModel = D('Contestants');
    $query['netid'] = session('contestant');
    $data = $ContestantsModel->where($query)->find();
    if($data === null){
      $this->redirect('logout',null,0, 'Redirecting ...');
      exit();
    }
    $this->assign('contestant',$data);
    $this->display();
  }

  public function _before_login(){
    if(is_login()){
      $this->redirect('index',null,0, 'Redirecting ...');
      exit();
    }
  }
  public function login(){
    $_POST['netid'] = strtolower($_POST['netid']);
    if(!IS_POST){
      $this->display();
      exit();
    }
    if(!session('recaptcha_passed') && empty($_POST['g-recaptcha-response'])){
      $errmsg = L('RECAPTCHA_EMPTY_MESSAGE');
    }
    elseif(empty($_POST['netid'])){
      $errmsg = L('NETID_EMPTY_MESSAGE');
    }
    elseif(!$this->verify_recaptcha()){
      $errmsg = L('RECAPTCHA_ERROR_MESSAGE');
    }
    elseif(!preg_match("/^[a-z][a-z0-9]{1,27}$/",$_POST['netid'])){
      $errmsg = L('NETID_FORMAT_ERROR_MESSAGE');
      unset($_POST['netid']);
    }
    if(!empty($errmsg)){
      $this->assign('errmsg',$errmsg);
      $this->display();
      exit();
    }
    $ContestantsModel = D('Contestants');
    $query['netid'] = $_POST['netid'];
    if($ContestantsModel->where($query)->limit('1')->count() == 1){
      session('contestant',$_POST['netid']);
      $this->redirect('index',null,0, 'Redirecting ...');
    }
    else{
      $this->redirect('register',array('netid'=>$_POST['netid']),0, 'Redirecting ...');
    }
  }

  public function _before_register(){
    if($this->is_login()){
      $this->redirect('index',null,0, 'Redirecting ...');
      exit();
    }
  }
  public function register(){
    if(!IS_POST){
      if(!session('recaptcha_passed')){
        //防跨站攻击
        unset($_GET['netid']);
      }
      $this->display();
      exit();
    }
    $_POST['netid'] = strtolower($_POST['netid']);
    $_POST['lastname'] = ucfirst($_POST['lastname']);
    $_POST['firstname'] = ucfirst($_POST['firstname']);
    if(!$this->verify_recaptcha()){
      $this->assign('errmsg','请重新进行人机身份验证');
      $this->display();
      exit();
    }
    $ContestantsModel = D('Contestants');
    $query['netid'] = I('post.netid','1invalid');
    if($ContestantsModel->where($query)->limit('1')->count() == 1){
      $this->assign('errmsg','该用户已报名参赛，请<a href="' . U('login') .'">点击此处以登录</a>');
      $this->display();
      exit();
    }
    if (!$ContestantsModel->create($_POST,1)){
      $this->assign('errmsg',$ContestantsModel->getError());
      $this->display();
      exit();
    }
    $ContestantsModel->add();
    session('recaptcha_passed',null);
    session('contestant',$_POST['netid']);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function logout(){
    session('recaptcha_passed',null);
    session('contestant',null);
    $this->redirect('login',null,0, 'Redirecting ...');
  }

  private function check_login(){
    if(!is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }

  private function verify_recaptcha(){
    //For non admin actions, every two step require a captcha check
    if(session('?recaptcha_passed')){
      session('recaptcha_passed',null);
      return true;
    }
    if(verify_recaptcha()){
      session('recaptcha_passed',true);
      return true;
    }
    else{
      return false;
    }
  }
}
