<?php
namespace Common\Model;
use Think\Model;
class ContestantsModel extends Model{
	protected $fields = array('netid','name','firstname','lastname','mobile','song','note','accompany_uploaded','confirmed','register_time');
  protected $pk='netid';
  protected $_validate = array(
    array('netid','require','请填写NETID',1,'regex',1),
    array('name','require','请填写姓名',1,'regex',1),
    array('firstname','require','请填写First Name',1,'regex',1),
    array('lastname','require','请填写First Name',1,'regex',1),
    array('mobile','require','必须填写手机号码',1,'regex',1),
    array('song','require','必须填写演唱歌曲',1,'regex',1),
    array('netid','/^[a-z][a-z0-9]{1,27}$/','NETID格式不正确',0,'regex',3),
    array('firstname','/^[A-Za-z]{1,50}$/','First Name格式不正确',0,'regex',3),
    array('lastname','/^[A-Za-z]{1,50}$/','Last Name格式不正确',0,'regex',3),
  );
}
