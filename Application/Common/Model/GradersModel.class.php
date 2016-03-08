<?php
namespace Common\Model;
use Think\Model;
class GradersModel extends Model{
	protected $fields = array('grader_id','grader_name');
  protected $pk='grader_id';
}
