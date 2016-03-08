<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends Controller {

  public function _before_index(){
    $this->check_login();
  }
  public function index(){
    $ContestantsModel = D('Contestants');
    $this->assign('contestants',$ContestantsModel->order('register_time asc')->select());
    $this->display();
  }

  public function login(){
    if(!IS_POST){
      $this->display();
      exit();
    }
    if(empty($_POST['g-recaptcha-response'])){
      $errmsg = L('RECAPTCHA_EMPTY_MESSAGE');
    }
    elseif(empty($_POST['pwd'])){
      $errmsg = L('ADMIN_PASSWORD_EMPTY_MESSAGE');
    }
    elseif(!verify_recaptcha()){
      $errmsg = L('RECAPTCHA_ERROR_MESSAGE');
    }
    elseif($_POST['pwd'] != C('ADMIN_PASSWORD')){
      $errmsg = L('ADMIN_PASSWORD_ERROR_MESSAGE');
    }
    if(!empty($errmsg)){
      $this->assign('errmsg',$errmsg);
      $this->display();
      exit();
    }
    session('admin',true);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function confirm(){
    if(!empty($_GET['netid'])){
      $ContestantsModel = D('Contestants');
      $query['netid'] = $_GET['netid'];
      $data['confirmed'] = 1;
      $ContestantsModel->where($query)->limit('1')->save($data);
    }
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function confirm_accompany(){
    if(!empty($_GET['netid'])){
      $ContestantsModel = D('Contestants');
      $query['netid'] = $_GET['netid'];
      $data['accompany_uploaded'] = 1;
      $ContestantsModel->where($query)->limit('1')->save($data);
    }
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function logout(){
    session('admin',null);
    $this->redirect('login',null,0, 'Redirecting ...');
  }

  private function is_login(){
    return session('?admin');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }
}
