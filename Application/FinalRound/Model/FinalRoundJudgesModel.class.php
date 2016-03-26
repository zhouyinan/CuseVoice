<?php
namespace FinalRound\Model;
use Think\Model;
class FinalRoundJudgesModel extends Model{
	protected $fields = array('judge_id','judge_name');
  protected $pk='judge_id';
}
