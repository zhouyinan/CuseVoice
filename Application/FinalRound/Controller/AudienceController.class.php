<?php
namespace FinalRound\Controller;
use Think\Controller;
class AudienceController extends Controller {
  public function index(){

  }

  public function login(){
    if(!empty($_GET['id'])){

    }
    else{
      $this->display();
    }
  }

  public function vote_status(){
    if(IS_AJAX){
      echo $this->get_vote_status();
    }
    else{
      echo 'Unauthroized';
    }
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

  private function get_vote_status(){
  //  return 'end';
    $status = F('audience_vote_status');
    if($status == 'end'){
      return 'end';
    }
    elseif($status == 'on'){
      return 'on';
    }
    else{
      return 'off';
    }
  }
}
