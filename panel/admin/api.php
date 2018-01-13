<?php
if(($_GET["user"])&&($_GET["pass"])){
	include("data/user.php");
	if(($_GET["user"]==$username)&&($_GET["pass"]==$password)){
		if($_GET["s"]){
			switch($_GET["s"]){
				case "command":
					if($_GET["value"]){
						
					}
				break;
				case "status":
					
				break;
			}
		}
		else{
			echo "true";
		}
	}
	else{
		echo "false";
	}
}
else{
	echo "false";
}
$configs = get_config("../../../Minecraft/server.properties");
function runcmd($command){
	include_once('page/rcon.php');
	$back = "";

	$host = 'localhost';
	$port = $configs["rcon.port"];
	$password = $configs["rcon.password"];
	$timeout = 3000;

	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect()){
		$back = $rcon->send_command($command);
		echo $back;
		$rcon->disconnect();
	}
	else{
		echo "连接失败！";
	}
}
function get_config($configfilename){
	$config = Array();
    $read = file_get_contents($configfilename);
	$getline = explode("\r\n",$read);
	for($i = 0;$i < count($getline);$i++){
		$target = explode("=",$getline[$i]);
		$config[$target[0]] = $target[1];
	}
	return $config;
}