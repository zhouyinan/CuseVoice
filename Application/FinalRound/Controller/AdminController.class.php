<?php
namespace FinalRound\Controller;
use Think\Controller;
class AdminController extends Controller {
  public function _before_index(){
    $this->check_login();
  }
  public function index(){

  }

  public function _before_generate_audience_id(){
    $this->check_login();
  }
  public function generate_audience_id(){
    $library = array();
    while(count($library) < 350){
      $library[mt_rand(100000,999999)] = 0;
    }
    $result = array();
    foreach($library as $k => $v){
      $result[] = array('audience_id'=>$k);
    }
    $m = D('FinalRoundAudience');
    $m->addAll($result);
  }

  public function _before_print_vote_tickets(){
    $this->check_login();
  }
  public function print_vote_tickets(){
    $m = D('FinalRoundAudience');
    $this->assign('ticket_list',$m->getField('audience_id',true));
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

  private function is_login(){
    return session('?admin');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }

  public function logout(){
    session('admin',null);
    $this->redirect('login',null,0, 'Redirecting ...');
  }
}
