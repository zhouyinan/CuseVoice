<?php
function getProgram($id){
  $library = array(
    1=>array(
      'name' => '开场表演：青春修炼手册',
      'cast' => array('何文林','周一楠','周梦妍','许广宇','蔡程航','王雨谦','周蕴仪','张彦杰','李奇珍','李一村'),
    ),
    2=>array(
      'name' => '歌曲：是否 - G.E.M',
      'cast' => array('何文林','周一楠'),
    ),
    3=>array(
      'name' => '第一轮第二组',
      'cast' => array('周梦妍','许广宇'),
    ),
    4=>array(
      'name' => '第一轮第三组',
      'cast' => array('蔡程航','王雨谦'),
    ),
    5=>array(
      'name' => '第一轮第四组',
      'cast' => array('周蕴仪','张彦杰')
    ),
    6=>array(
      'name' => '第一轮第五组',
      'cast' => array('李奇珍','李一村')
    ),
  );
  return $library[$id];
}

function getCurrentProgram(){
  return getProgram(getCurrentProgramID());
}

function getCurrentProgramID(){
  return F('CurrentProgramID')?:1;
}

function get_cast_info($name){
  $library = array(
    '何文林' => array(
      'name' => '何文林',
      'desc' => '毛线清清不吃素的Ariel',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周一楠' => array(
      'name' => '周一楠',
      'desc' => '傻蠢音痴老司机',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周梦妍' => array(
      'name' => '周梦妍',
      'desc' => '死亡颂唱',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '许广宇' => array(
      'name' => '许广宇',
      'desc' => '灵魂歌手',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '蔡程航' => array(
      'name' => '蔡程航',
      'desc' => '长腿大叔爱健身',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '王雨谦' => array(
      'name' => '王雨谦',
      'desc' => '没高音的建筑狗',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周蕴仪' => array(
      'name' => '周蕴仪',
      'desc' => '雪城污妖王Sunshine',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '张彦杰' => array(
      'name' => '张彦杰',
      'desc' => '雪城老干部',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '李奇珍' => array(
      'name' => '李奇珍',
      'desc' => '走心情歌小王菲',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '李一村' => array(
      'name' => '李一村',
      'desc' => '高音上不去低音下不来的男中音',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
  );
  if($name == 'all'){return $library;}
  return $library[$name];
}

function get_judges(){
  return array('李运珂','夏伊璠','于蓝妮','张露心','张姗姗');
}
?>
