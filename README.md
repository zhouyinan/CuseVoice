# CuseVoice

这是一个因雪城大学华人学生学者联合会举办雪城好声音大赛要求而编写的报名、评分系统，使用了[ThinkPHP](http://document.thinkphp.cn)框架。

## 运行环境
* PHP（与[ThinkPHP](http://document.thinkphp.cn)框架要求相同：PHP5.3以上版本，不支持PHP5.3dev版本和PHP6）
* MySQL（需支持InnoDB引擎）

请特别注意文件权限，运行时ThinkPHP框架会自动创建Runtime目录，可能需要写权限

## 安装

### 配置

配置文件位于 Application/Common/Conf/config.php

请根据文件内的注释进行配置

### 建立数据表

1. 在MySQL内建立以InnoDB为引擎的数据库
2. 在该数据中运行install.sql
