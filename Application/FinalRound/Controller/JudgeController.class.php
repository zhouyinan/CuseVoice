<?php
namespace FinalRound\Controller;
use Think\Controller;
class JudgeController extends Controller {
  public function _before_index(){
    $this->check_login();
  }
  public function index(){
    $GradeModel = D('FinalRoundGrades');
    $query['judge'] = session('judge');
    $query['status'] = 'ungraded';
    $tasks = $GradeModel->where($query)->select();
    if (count($tasks)==0) {
      $this->display('index_notask');
    }
    else {
      $this->assign('tasks',$tasks);
      $this->display();
    }
  }

  public function _before_refresh_check(){
    $this->check_login();
  }
  public function refresh_check(){
    $GradeModel = D('FinalRoundGrades');
    $query['judge'] = session('judge');
    $query['status'] = 'ungraded';
    echo ($GradeModel->where($query)->count() == 0)?'no':'yes';
  }

  public function login(){
    $this->assign('judges_list',get_judges());
    if(!IS_POST){
      $this->display();
      return;
    }
    if(empty($_POST['g-recaptcha-response'])){
      $errmsg = '请进行人机身份验证';
    }
    elseif(empty($_POST['pwd'])){
      $errmsg = '请输入登录密码';
    }
    elseif(!verify_recaptcha()){
      $errmsg = '请重新进行人机身份验证';
    }
    elseif($_POST['pwd'] != C('JUDGE_PASSWORD')){
      $errmsg = '登录密码错误';
    }
    if(!empty($errmsg)){
      $this->assign('errmsg',$errmsg);
      $this->display();
      exit();
    }
    session('judge',$_POST['judge']);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  private function is_login(){
    return session('?judge');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }

  public function submitScore(){
    $GradeModel = D('FinalRoundGrades');
    $query['judge'] = session('judge');
    foreach($_POST['grade'] as $k => $grade){
      $query['round'] = $grade['round'];
      $query['contestant'] = $grade['name'];
      $data['status'] = 'done';
      if($grade['score']>100 || $grade['score']<1){
        continue;
      }
      else{
        $data['score'] = $grade['score'];
        $GradeModel->where($query)->save($data);
      }
    }
    $this->redirect('index',null,0, 'Redirecting ...');
  }
}
