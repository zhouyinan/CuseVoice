<?php
namespace FinalRound\Controller;
use Think\Controller;
class AudienceController extends Controller {
  public function _before_index(){
    $this->check_login();
  }
  public function index(){
    switch($this->status()){
      case 'current_program':
        $this->current_program();
        return;
      case 'second_round':
        $this->second_round();
        return;
      case 'vote_end':
        $this->display('vote_end');
        return;
      case 'vote_off':
        $this->display('vote_off');
        return;
      case 'vote_on':
        $this->vote();
        return;
      default:
        $this->display('index_off');
        return;
    }
  }

  public function second_round(){
    //var_dump(get_cast_info(F('CurrentProgramID')));
    $this->assign('cast',get_cast_info(F('CurrentProgramID')));
    $this->display('second_round');
  }

  public function login(){
    if(!empty($_REQUEST['audience_id'])){
      $model = D('FinalRoundAudience');
      $query['audience_id'] = $_REQUEST['audience_id'];
      if($model->where($query)->find()===null){
        $this->assign('errmsg','该观众ID无效');
        $this->display();
      }
      else{
        session('audience_id',$_REQUEST['audience_id']);
        $this->redirect('index',null,0, 'Redirecting ...');
      }
    }
    else{
      $this->display();
    }
  }

  public function get_status(){
    if(IS_AJAX){
      echo $this->status();
    }
    else{
      echo 'Unauthroized';
    }
  }

  public function get_current_program(){
    if(IS_AJAX){
      echo getCurrentProgramID();
    }
    else{
      echo 'Unauthroized';
    }
  }

  private function current_program(){
    $program = getCurrentProgram();
    $this->assign('name',$program['name']);
    foreach($program['cast'] as $k => $v){
      $casts[] = get_cast_info($v);
    }
    $this->assign('casts',$casts);
    $this->display('current_program');
  }

  private function vote(){
    $VoteModel = D('FinalRoundVotes');
    $q['round'] = 2;
    $GradesModel = D('FinalRoundGrades');
    $this->assign('contestants_list',$GradesModel->where($q)->group('contestant')->getField('contestant',true));
    if(IS_POST){
      if(count($_POST['selection'])!=3){
        echo '<script>alert(\'您必须选择3位选手\');</script>';
        $this->display('vote');
        return;
      }
      $query['audience_id'] = session('audience_id');
      if($VoteModel->where($query)->find() === null){
        foreach($_POST['selection'] as $k => $v){
          $data[] = array('audience_id'=>session('audience_id'),'contestant'=>$v);
        }
        $VoteModel->addAll($data);
      }
      $this->display('vote_success');
      return;
    }
    $query['audience_id'] = session('audience_id');
    if($VoteModel->where($query)->find()===null){
      $this->display('vote');
      return;
    }
    $this->display('vote_success');

  }

  private function status(){
    return F('FinalRoundAudienceStatus')?:'off';
  }

  private function is_login(){
    return session('?audience_id');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }
}
