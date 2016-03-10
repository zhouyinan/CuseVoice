<?php
namespace Common\Model;
use Think\Model;
class FinalRoundGradesModel extends Model{
	protected $fields = array('grade_id','contestant_id','judge','score','round');
  protected $pk='grade_id';
  protected $_validate = array(
    array('contestant_id','require','参赛选手信息错误',1,'regex',1),
    array('judge','require','评委信息错误',1,'regex',1),
    array('score','require','请填写分数',1,'regex',1),
    array('score','0,100','分数应当介于0~100之间',0,'between',3),
    array('score','/^[0-9]{1,3}$/','分数格式错误',0,'regex',3),
  );
}
