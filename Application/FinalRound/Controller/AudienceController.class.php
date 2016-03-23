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
}
