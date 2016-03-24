<?php
function getProgram($id){
  $library = array(
    array(
      'name' => '开场表演：青春修炼手册',
      'cast' => array('何文林','周一楠','周梦妍','许广宇','蔡程航','王雨谦','周蕴仪','张彦杰','李奇珍','李一村'),
    ),
    array(
      'name' => '歌曲：是否 - G.E.M',
      'cast' => array('何文林','周一楠'),
    ),
  );
  return $library[$id];
}

function getCurrentProgram(){
  return getProgram(F('CurrentProgramID')?:0);
}

function get_cast_info($name){
  $library = array(
    '何文林' => array(
      'name' => $name,
      'desc' => '毛线清清不吃素的Ariel',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周一楠' => array(
      'name' => $name,
      'desc' => '傻蠢音痴老司机',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周梦妍' => array(
      'name' => $name,
      'desc' => '死亡颂唱',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '许广宇' => array(
      'name' => $name,
      'desc' => '灵魂歌手',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '蔡程航' => array(
      'name' => $name,
      'desc' => '长腿大叔爱健身',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '王雨谦' => array(
      'name' => $name,
      'desc' => '没高音的建筑狗',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '周蕴仪' => array(
      'name' => $name,
      'desc' => '雪城污妖王Sunshine',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '张彦杰' => array(
      'name' => $name,
      'desc' => '雪城老干部',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '李奇珍' => array(
      'name' => $name,
      'desc' => '走心情歌小王菲',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
    '李一村' => array(
      'name' => $name,
      'desc' => '高音上不去低音下不来的男中音',
      'avatar' => 'https://voice.sucssa.pumpkin.name/Public/avatar/' . $name .  '.jpg'
    ),
  );
  return $library[$name];
}
?>
