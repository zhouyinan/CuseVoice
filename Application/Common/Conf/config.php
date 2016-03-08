<?php
return array(
	'DEFAULT_MODULE'        =>  'Home',
	'DEFAULT_TIMEZONE'      =>  'EST',
	'DEFAULT_LANG'          =>  'zh-cn',
	'URL_MODEL'             =>  2,
	'URL_HTML_SUFFIX'       =>  '',
	'DB_DEBUG'  =>  FALSE,
	'TMPL_EXCEPTION_FILE'     =>  APP_PATH . 'Common/View/exception.html',
	'ERROR_MESSAGE'         =>  '页面不存在或系统错误',
	'LANG_SWITCH_ON' => true,
	'LANG_AUTO_DETECT' => true,
	'LANG_LIST'        => 'zh-cn',
	'VAR_LANGUAGE'     => 'lang',

	//设置密码
	'ADMIN_PASSWORD' => '123456', //管理密码
	'GRADER_PASSWORD' => '123456', //评委密码

	//在下方填写数据库设置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => '', // 数据库名
	'DB_USER'   => '', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集


	//下方填写Google Recaptcha配置
	'RECAPTCHA_SERVER_SECRET' => '',
	'RECAPTCHA_CLIENT_KEY' => '',

	//其他配置
	'FAVICON_URL' => '',
);
