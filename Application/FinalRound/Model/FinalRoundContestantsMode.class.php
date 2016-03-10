<?php
namespace Common\Model;
use Think\Model;
class FinalRoundContestantsModel extends Model{
	protected $fields = array('contestant_id','name','firstname','lastname');
  protected $pk='contestant_id';
  protected $_validate = array(
    array('name','require','请填写姓名',1,'regex',1),
    array('firstname','require','请填写First Name',1,'regex',1),
    array('lastname','require','请填写First Name',1,'regex',1),
    array('firstname','/^[A-Za-z]{1,50}$/','First Name格式不正确',0,'regex',3),
    array('lastname','/^[A-Za-z]{1,50}$/','Last Name格式不正确',0,'regex',3),
  );
}
