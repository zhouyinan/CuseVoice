<?php
namespace Admin\Controller;
use Think\Controller;
class JudgeController extends Controller {

  public function _before_index(){
    $this->check_login();
  }

  public function index(){
    $this->assign('judge_name',$this->getJudgeName());
    if(F('current_contestant_netid')===false){
      $this->assign('msg','打分环节尚未开始');
      $this->assign('refresh',5);
      $this->display('index_msg');
      exit();
    }
    $GradesModel = D('Grades');
    $query['contestant_netid'] = F('current_contestant_netid');
    $query['judge'] = session('judge');
    if(IS_POST){
      if($GradesModel->where($query)->find() === NULL){
        if(!$GradesModel->create(array('contestant_netid'=>$_POST['contestant_netid'],'judge'=>session('judge'),'score'=>$_POST['score']),1)){
          $this->assign('msg',$GradesModel->getError());
          $this->assign('refresh',3);
          $this->display('index_msg');
          exit();
        }
        $GradesModel->add();
      }
    }
    $record = $GradesModel->where($query)->find();
    if($record === NULL){
      $this->assign('contestant',$this->get_contestant());
      $this->display();
    }
    else{
      $this->assign('msg','您已成功完成打分');
      $this->assign('refresh',6);
      $this->display('index_msg');
      exit();
    }
  }

  public function login(){
    $GradersModel = D('Judges');
    $this->assign('judges_list',$GradersModel->order('judge_id asc')->select());
    if(!IS_POST){
      $this->display();
      exit();
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

  public function logout(){
    session('judge',null);
    $this->redirect('login',null,0, 'Redirecting ...');
  }

  private function get_contestant(){
    $ContestantsModel = D('Contestants');
    $query['netid'] = F('current_contestant_netid');
    return $ContestantsModel->field('firstname,lastname,netid,name,song')->where($query)->find();
  }

  private function is_login(){
    return session('?judge');
  }

  private function getJudgeName($judge_id = ''){
    $judge_id = $judge_id?:session('judge');
    if(F('JUDGE-NAME-' . $judge_id)===false){
      $JudgesModel = D('Judges');
      $query['judge_id'] = $judge_id;
      F('JUDGE-NAME-' . $judge_id,$JudgesModel->where($query)->getField('judge_name'));
    }
    return F('JUDGE-NAME-' . $judge_id);
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }
}
