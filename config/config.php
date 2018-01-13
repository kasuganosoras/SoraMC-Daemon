<?php
$password = "password";//Daemon链接密码
$bindport = 26817;//Daemon绑定端口[小于1024需要root]
$bindhost = "localhost";//Daemon绑定ip[建议使用localhost或127.0.0.1]
$daemonid = 1;//daemonid
$aesenkey = "EncryptKey0Ex!sF?xwA09xf52";//链接密码
$contoken = "AaBbCcDdEeFfGgHhIiJjKkLlMn";//不用管就好
$httpport = 21567;//日志绑定端口[小于1024需要root]
$httpmrys = 512;//http服务器内存大小,单位为MB
$corename = "server.jar";//你的jar名字
$jvmmaxmr = 1024;//内存大小,单位为MB
$javapath = "java";//java路径,path设置过请用java