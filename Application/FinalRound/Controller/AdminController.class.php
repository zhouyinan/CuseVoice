<?php
namespace FinalRound\Controller;
use Think\Controller;
class AdminController extends Controller {
  public function index(){

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

  public function print_vote_tickets(){
    $m = D('FinalRoundAudience');
    $this->assign('ticket_list',$m->getField('audience_id',true));
    $this->display();
  }
}
