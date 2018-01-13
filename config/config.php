<?php
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//一定要按照规范写!!!!!
//重要的事情说10编
$config = array(
    "TPS" => 5,//Daemon处理频率[Ticks Per Second(帧每秒)][建议使用5的整数倍,否则可能需要round,无法精确]
    "Password" => "password",//链接密码
    "DaemonPort" => 26817,//Daemon通讯端口
    "LogPort" => 21567,//日志服务器端口
    "LogMemories" => 512,//日志服务器内存大小
    "ServerMemories" => 1024,//服务器内存大小
    "CoreName" => "server.jar",//核心名字
    "ServerHost" => "localhost",//服务器绑定IP[建议使用localhost或127.0.0.1]
    "DaemonID" => "1",//DaemonID
    "AESToken" => "EncryptKey0Ex!sF?xwA09xf52",//字面意思
    "ConToken" => "AaBbCcDdEeFfGgHhIiJjKkLlMn",//不用管就好
    "JavaCommand" => "java"//java路径,path设置过请用java
);
/* @Deprecated [被dhdj弃用的config]*/
//以下为渣渣原作者的渣渣config
/*
$password = "password";Daemon链接密码
$bindport = 26817;Daemon绑定端口[小于1024需要root]
$bindhost = "localhost";Daemon绑定ip[建议使用localhost或127.0.0.1]
$daemonid = 1;daemonid
$aesenkey = "EncryptKey0Ex!sF?xwA09xf52";链接密码
$contoken = "AaBbCcDdEeFfGgHhIiJjKkLlMn";不用管就好
$httpport = 21567;日志绑定端口[小于1024需要root]
$httpmrys = 512;http服务器内存大小,单位为MB
$corename = "server.jar";你的jar名字
$jvmmaxmr = 1024;内存大小,单位为MB
$javapath = "java";java路径,path设置过请用java
*/
//以上为渣渣原作者的渣渣config