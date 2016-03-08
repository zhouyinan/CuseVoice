<?php
namespace Common\Model;
use Think\Model;
class JudgesModel extends Model{
	protected $fields = array('judge_id','judge_name');
  protected $pk='judge_id';
}
