<?php
namespace FinalRound\Controller;
use Think\Controller;
class AdminController extends Controller {
  private $progress_library = array(
    1 => array(
      'name' => '开场表演：青春修炼手册',
      'desc' => '全体选手与主持人',
      'program_id' => 1,
    ),
    2 => array(
      'name' => '第一轮 第一组（是否 - G.E.M）',
      'desc' => '何文林 / 周一楠',
      'program_id' => 2,
      'contestants' => array('何文林','周一楠'),
      'round' => 1,
    ),
    3 => array(
      'name' => '第一轮 第二组',
      'desc' => '周梦妍 / 许广宇',
      'contestants' => array('周梦妍','许广宇'),
      'program_id' => 3,
      'round' => 1,
    ),
    4 => array(
      'name' => '第一轮 第三组',
      'desc' => '蔡程航 / 王雨谦',
      'contestants' => array('蔡程航','王雨谦'),
      'program_id' => 4,
      'round' => 1,
    ),
    5 => array(
      'name' => '第一轮  第四组',
      'desc' => '周蕴仪 / 张彦杰',
      'contestants' => array('周蕴仪','张彦杰'),
      'program_id' => 5,
      'round' => 1,
    ),
    6 => array(
      'name' => '第一轮 第五组',
      'desc' => '李奇珍 / 李一村',
      'contestants' => array('李奇珍','李一村'),
      'program_id' => 6,
      'round' => 1,
    ),
  );

  public function _before_index(){
    $this->check_login();
  }
  public function index(){
    $this->assign('progress_list',$this->progress_library);
    $this->assign('contestants_list',get_cast_info('all'));
    $this->display();
  }

  public function _before_set_vote_state(){
    $this->check_login();
  }
  public function set_vote_state(){
    switch($_GET['status']){
      case 'off':
        F('FinalRoundAudienceStatus','vote_off');
        break;
      case 'on':
        F('FinalRoundAudienceStatus','vote_on');
        break;
      case 'end':
        F('FinalRoundAudienceStatus','vote_end');
        break;
    }
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function _before_generate_audience_id(){
    $this->check_login();
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

  public function _before_print_vote_tickets(){
    $this->check_login();
  }
  public function print_vote_tickets(){
    $m = D('FinalRoundAudience');
    $this->assign('ticket_list',$m->getField('audience_id',true));
    $this->display();
  }

  public function login(){
    if(!IS_POST){
      $this->display();
      exit();
    }
    if(empty($_POST['g-recaptcha-response'])){
      $errmsg = L('RECAPTCHA_EMPTY_MESSAGE');
    }
    elseif(empty($_POST['pwd'])){
      $errmsg = L('ADMIN_PASSWORD_EMPTY_MESSAGE');
    }
    elseif(!verify_recaptcha()){
      $errmsg = L('RECAPTCHA_ERROR_MESSAGE');
    }
    elseif($_POST['pwd'] != C('ADMIN_PASSWORD')){
      $errmsg = L('ADMIN_PASSWORD_ERROR_MESSAGE');
    }
    if(!empty($errmsg)){
      $this->assign('errmsg',$errmsg);
      $this->display();
      exit();
    }
    session('admin',true);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  private function is_login(){
    return session('?admin');
  }

  private function check_login(){
    if(!$this->is_login()){
      $this->redirect('login',null,0, 'Redirecting ...');
      exit();
    }
  }

  public function logout(){
    session('admin',null);
    $this->redirect('login',null,0, 'Redirecting ...');
  }

  public function _before_set_current_progress(){
    $this->check_login();
  }
  public function set_current_progress(){
    if($this->progress_library[$_GET['id']]===null){
      E('状态参数错误');
      exit();
    }
    $progress = $this->progress_library[$_GET['id']];
    F('FinalRoundAudienceStatus','current_program');
    F('CurrentProgramID',$progress['program_id']);
    foreach($progress['contestants'] as $k => $contestant){
      foreach(get_judges() as $k => $judge){
        $data[] = array('contestant'=>$contestant,'round'=>$progress['round'],'judge'=>$judge,'status'=>'ungraded');
      }
    }
    $model = D('FinalRoundGrades');
    $model->addAll($data);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function _before_set_round2_contestant(){
    $this->check_login();
  }
  public function set_round2_contestant(){
    F('CurrentProgramID',$_GET['contestant']);
    F('FinalRoundAudienceStatus','second_round');
    foreach(get_judges() as $k => $judge){
      $data[] = array('contestant'=>$_GET['contestant'],'round'=>2,'judge'=>$judge,'status'=>'ungraded');
    }
    $model = D('FinalRoundGrades');
    $model->addAll($data);
    $this->redirect('index',null,0, 'Redirecting ...');
  }

  public function grade(){
    $GradesModel = D('FinalRoundGrades');
    $query['status'] = 'done';
    $query['round'] = 1;
    $result = $GradesModel->field('round,contestant,count(*),sum(score),max(score),min(score),avg(score)')->where($query)->group('contestant,round')->select();
    foreach($result as $k => $score){
      $round1_list[$score['contestant']] = $score;
      if($score['count(*)']<=2){
        $round1[$score['contestant']] = $score['avg(score)'] + 0;
      }
      else{
        $round1[$score['contestant']] = ($score['sum(score)'] - $score['min(score)'] - $score['max(score)']) / ($score['count(*)'] - 2);
      }
    }
    arsort($round1);
    foreach($round1 as $name => $final_score){
      $round1_final_list[$name] = $round1_list[$name];
      $round1_final_list[$name]['final'] = $final_score;
    }
    $this->assign('round1',$round1_final_list);
    var_dump($round1_final_list);
    $VotesModel = D('FinalRoundVotes');
    $votes_result = $VotesModel->field('count(*),contestant')->group('contestant')->select();
    foreach($votes_result as $k => $v){
      $votes[$v['contestant']] = $v['count(*)'];
    }
    $query['round'] = 2;
    $result = $GradesModel->field('round,contestant,count(*),sum(score),max(score),min(score),avg(score)')->where($query)->group('contestant,round')->select();
    foreach($result as $k => $score){
      $round2_list[$score['contestant']] = $score;
      $round2_list[$score['contestant']]['votes'] = $votes[$score['contestant']]?:0;
      if($score['count(*)']<=2){
        $round2[$score['contestant']] = $score['avg(score)'] + 0;
      }
      else{
        $round2[$score['contestant']] = ($score['sum(score)'] - $score['min(score)'] - $score['max(score)']) / ($score['count(*)'] - 2);
      }
      $round2[$score['contestant']] = $round2[$score['contestant']] + ($votes[$score['contestant']] / 5);
    }
    arsort($round2);
    foreach($round2 as $name => $final_score){
      $round2_final_list[$name] = $round2_list[$name];
      $round2_final_list[$name]['final'] = $final_score;
    }
    var_dump($round2_final_list);
  }
}
