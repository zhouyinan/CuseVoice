<?php
namespace FinalRound\Controller;
use Think\Controller;
class AudienceController extends Controller {
  public function index(){
    switch($this->status()){
      case 'current_program':
        $this->current_program();
        return;
      default:
        $this->display('index_off');
        return;
    }
  }

  public function login(){
    if(!empty($_GET['id'])){

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

  private function current_program(){
    $program = getCurrentProgram();
    $this->assign('name',$program['name']);
    foreach($program['cast'] as $k => $v){
      $casts[] = get_cast_info($v);
    }
    $this->assign('casts',$casts);
    $this->display('current_program');
  }

  public function vote(){
    $status = $this->get_vote_status();
    //$status="end";
    if($status == 'end'){
      $this->display('vote_msg_end');
    }
    elseif($status == 'on'){
      $this->display();
    }
    else{
      $this->display('vote_msg_off');
    }
  }

  private function status(){
    return 'current_program';
    return F('FinalRoundAudienceStatus')?:'off';
  }
}
