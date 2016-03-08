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

  public function _before_judges_list(){
    $this->check_login();
  }
  public function judges_list(){
    $JudgesModel = D('Judges');
    $this->assign('judges_list',$JudgesModel->order('judges_id asc')->select());
    $this->display();
  }

  public function _before_judge_add(){
    $this->check_login();
  }
  public function judge_add(){
    $JudgesModel = D('Judges');
    if(empty($_GET['judges_id'])){
      E('评委ID参数错误');
    }
    $data['judges_id'] = I('get.judges_id');
    $JudgesModel->add($data);
    $this->redirect('judges_list',null,0, 'Redirecting ...');
  }

  public function _before_judge_delete(){
    $this->check_login();
  }
  public function judge_delete(){
    $JudgesModel = D('Judges');
    if(empty($_GET['judges_id'])){
      E('评委ID参数错误');
    }
    $query['judges_id'] = I('get.judges_id');
    $JudgesModel->where($query)->limit('1')->delete();
    $this->redirect('judges_list',null,0, 'Redirecting ...');
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
