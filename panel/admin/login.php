<?php
/**
 *           PHPMC Minecraft 服务器管理面板
 *
 *                                             PHPMC Ver 3.0
 *
 *       本程序由jianghao7172开发，所属NiconicoCraft团队发布，
 *
 *   任何人可以自由下载、使用、传播本程序，但是未经作者本人同
 *
 *   意，禁止修改后二次发布，以及用于商业目的，否则视为侵权行
 *
 *   为，我们将会追究侵权者的责任。
 *
 *       项目官网：http://www.phpmc.net/  QQ群：602945616
 */
OB_START();
SESSION_START();
if(!isset($_SERVER['PHP_AUTH_USER'])) {
	header('WWW-Authenticate: Basic realm="NiconicoCraft"');
	header('HTTP/1.0 401 Unauthorized');
	echo "<script>alert('登陆失败，账号或密码错误。');top.location='../';</script>";
	exit;
}
else {
	if($_GET["s"]=="logout"){
		echo "<script>alert('登出成功！');top.location='./login.php';</script>";
		exit;
	}
	include("data/user.php");
	if($_SERVER['PHP_AUTH_PW']){
		if(!preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/",$_SERVER["PHP_AUTH_USER"])){
			echo "<script>alert('非法用户名，已被系统阻止。');top.location='../';</script>";
			exit;
		}
		if(($password==sha1(md5($_SERVER['PHP_AUTH_PW'])))&&($username==$_SERVER['PHP_AUTH_USER'])){
			$_SESSION["adminuser"] = $_SERVER["PHP_AUTH_USER"];
			echo "<script>alert('登陆成功！');top.location='./index.php';</script>";
		}
		else{
			header('WWW-Authenticate: Basic realm="NiconicoCraft"');
			header('HTTP/1.0 401 Unauthorized');
			echo "<script>alert('登陆失败，账号或密码错误。');top.location='./login.php';</script>";
			exit;
		}
	}
	else{
		header('WWW-Authenticate: Basic realm="NiconicoCraft"');
		header('HTTP/1.0 401 Unauthorized');
		echo "<script>alert('登陆失败，账号或密码错误。');top.location='./login.php';</script>";
		exit;
	}
}