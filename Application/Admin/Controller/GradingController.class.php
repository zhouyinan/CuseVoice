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

  public function judges_list(){
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
}
