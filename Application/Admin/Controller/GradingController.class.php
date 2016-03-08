<?php
namespace Admin\Controller;
use Think\Controller;
class GradingController extends Controller {

  public function _before_index(){
    $this->check_login();
  }
  public function index(){
    $GradesModel = D('Grades');
    $result = $GradesModel->field('name,lastname,firstname,count(*),sum(score),max(score),min(score),song,avg(score)')->join('__CONTESTANTS__ ON __GRADES__.contestant_netid = __CONTESTANTS__.netid')->group('contestant_netid')->select();
    foreach($result as $k => $contestant){
      if($contestant['count(*)']<=2){
        $result[$k]['final'] = $contestant['avg(score)'];
        $score[$k] = $result[$k]['final'];
      }
      else{
        $result[$k]['final'] = ($contestant['sum(score)'] - $contestant['min(score)'] - $contestant['max(score)']) / ($contestant['count(*)'] - 2);
        $score[$k] = $result[$k]['final'];
      }
    }
    arsort($score);
    foreach($score as $k => $v){
      $list[] = $result[$k];
    }
    $this->assign('list',$list);
    $this->display();
  }

  public function audience_index(){
    $GradesModel = D('Grades');
    $result = $GradesModel->field('name,lastname,firstname,count(*),sum(score),max(score),min(score),song,avg(score)')->join('__CONTESTANTS__ ON __GRADES__.contestant_netid = __CONTESTANTS__.netid')->group('contestant_netid')->select();
    foreach($result as $k => $contestant){
      if($contestant['count(*)']<=2){
        $result[$k]['final'] = $contestant['avg(score)'];
        $score[$k] = $result[$k]['final'];
      }
      else{
        $result[$k]['final'] = ($contestant['sum(score)'] - $contestant['min(score)'] - $contestant['max(score)']) / ($contestant['count(*)'] - 2);
        $score[$k] = $result[$k]['final'];
      }
    }
    foreach($score as $k => $v){
      $list[] = $result[$k];
    }
    $this->assign('list',$list);
    $this->display();
  }

  public function graders(){
    $GradersModel = D('Graders');
    $this->assign('graders_list',$GradersModel->order('grader_id asc')->select());
    $this->display();
  }

  private function is_login(){
    return session('?admin');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('Dashboard/login',null,0, 'Redirecting ...');
      exit();
    }
  }

  public function grader_login(){
    $GradersModel = D('Graders');
    $this->assign('graders_list',$GradersModel->order('grader_id asc')->select());
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
    elseif($_POST['pwd'] != C('GRADER_PASSWORD')){
      $errmsg = '登录密码错误';
    }
    if(!empty($errmsg)){
      $this->assign('errmsg',$errmsg);
      $this->display();
      exit();
    }
    session('grader',$_POST['grader']);
    $this->redirect('grader_index',null,0, 'Redirecting ...');
  }

  public function grader_logout(){
    session('grader',null);
    $this->redirect('grader_login',null,0, 'Redirecting ...');
  }

  public function _before_grader_index(){
    $this->check_grader_login();
  }

  public function _before_setCurrentContestant(){
    $this->check_login();
  }
  public function setCurrentContestant(){
    if(empty($_GET['netid'])){
      E('选手参数错误');
    }
    F('current_contestant_netid',$_GET['netid']);
    $this->redirect('Admin/Dashboard/index',null,0, 'Redirecting ...');
  }

  public function grader_index(){
    $this->assign('grader_name',$this->getGraderName());
    if(F('current_contestant_netid')===false){
      $this->assign('msg','打分环节尚未开始');
      $this->assign('refresh',5);
      $this->display('grader_index_msg');
      exit();
    }
    $GradesModel = D('Grades');
    $query['contestant_netid'] = F('current_contestant_netid');
    $query['grader'] = session('grader');
    if(IS_POST){
      if($GradesModel->where($query)->find() === NULL){
        if(!$GradesModel->create(array('contestant_netid'=>$_POST['contestant_netid'],'grader'=>session('grader'),'score'=>$_POST['score']),1)){
          $this->assign('msg',$GradesModel->getError());
          $this->assign('refresh',3);
          $this->display('grader_index_msg');
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
      $this->display('grader_index_msg');
      exit();
    }
  }

  private function get_contestant(){
    $ContestantsModel = D('Contestants');
    $query['netid'] = F('current_contestant_netid');
    return $ContestantsModel->field('firstname,lastname,netid,name,song')->where($query)->find();
  }

  private function is_grader_login(){
    return session('?grader');
  }

  private function getGraderName($grader_id = ''){
    $grader_id = $grader_id?:session('grader');
    if(F('GRADER-NAME-' . $grader_id)===false){
      $GradersModel = D('Graders');
      $query['grader_id'] = $grader_id;
      F('GRADER-NAME-' . $grader_id,$GradersModel->where($query)->getField('grader_name'));
    }
    return F('GRADER-NAME-' . $grader_id);
  }

  private function check_grader_login(){
    if(!$this->is_grader_login()){
      $this->redirect('grader_login',null,0, 'Redirecting ...');
      exit();
    }
  }
}
