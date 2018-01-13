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
if(!$_SESSION["adminuser"]){
	exit;
}
function get_config($configfilename)
{
	$config = Array();
    $read = file_get_contents($configfilename);
	$getline = explode("\r\n",$read);
	for($i = 0;$i < count($getline);$i++){
		$target = explode("=",$getline[$i]);
		$config[$target[0]] = $target[1];
	}
	return $config;
}
$configs = get_config("../../../../Minecraft/server.properties");
function NiconicoCraft_Query($IP, $Port = 25565,$Timeout = 2){
	$Socket = Socket_Create(AF_INET,SOCK_STREAM,SOL_TCP);
    Socket_Set_Option($Socket,SOL_SOCKET,SO_SNDTIMEO,array('sec'=>(int)$Timeout,'usec'=>0));
    Socket_Set_Option($Socket,SOL_SOCKET,SO_RCVTIMEO,array('sec'=>(int)$Timeout,'usec'=>0));
    if($Socket===FALSE||@Socket_Connect($Socket,$IP,(int)$Port)===FALSE){
        return FALSE;
    }
    Socket_Send($Socket,"\xFE\x01",2,0);
    $Len = Socket_Recv( $Socket, $Data, 512, 0 );
    Socket_Close($Socket);
    if($Len<4||$Data[0]!=="\xFF"){
        return FALSE;
    }
    $Data = SubStr($Data,3);
    $Data = iconv('UTF-16BE','UTF-8',$Data);
    if($Data[1]==="\xA7"&&$Data[2]==="\x31"){
        $Data = Explode("\x00",$Data);
        return Array(
            'HostName' => $Data[3],
            'Players' => IntVal($Data[4]),
            'MaxPlayers' => IntVal($Data[5]),
            'Protocol' => IntVal($Data[1]),
            'Version' => $Data[2],
        );
    }
    $Data = Explode("\xA7",$Data);
    return Array(
        'HostName' => SubStr( $Data[ 0 ], 0, -1 ),
        'Players' => isset( $Data[ 1 ] ) ? IntVal( $Data[ 1 ] ) : 0,
        'MaxPlayers' => isset( $Data[ 2 ] ) ? IntVal( $Data[ 2 ] ) : 0,
        'Protocol' => 0,
        'Version' => '1.3',
    );
}
$configs = get_config("../../../../Minecraft/server.properties");
$mcserverip = 'localhost';
$mcserverport = $configs["server-port"];
$array = NiconicoCraft_Query($mcserverip ,$mcserverport); //调用API
$name = $array['HostName']; //服务器名称
$max = $array['MaxPlayers']; //最大玩家数量
$online = $array['Players']; //当前玩家数量
$version = $array['Version']; //服务器版本
switch($_GET["s"]){
	case "start":
		if($version){
			echo "服务器已经在运行中，不能重复启动";
			exit;
		}
		if(!file_exists("../../../../Minecraft/server.properties")){
			file_put_contents("../../../../Minecraft/server.properties","rcon.port=8234\r\nrcon.password=".rand(100000000,999999999));
		}
		if(file_exists("../../../../Minecraft/logs.temp")){
			unlink("../../../../Minecraft/logs.temp");
		}
		//file_put_contents("../../../../Minecraft/logs.temp","PHPMC 3 Minecraft Server Manager\nCopyRight (C) 2012-2017 NiconicoCraft All Right Reserved.");
		system("ThreadRun.exe");//cmd /c cd ../../../../Minecraft/&java -Xmx1024M -Xms1024M -jar PaperSpigot.jar>>logs.temp
		echo "成功开启服务器！";
	break;
	case "stop":
		if(!$version){
			echo "服务器已经是停止状态了";
			exit;
		}
		include_once('rcon.php'); //引用类文件
		$back = ""; //初始化返回数据

		$host = 'localhost'; // 服务器的ip地址
		$port = $configs["rcon.port"]; // rcon端口，在服务器配置文件里的 rcon.port= 一行
		$password = $configs["rcon.password"]; // rcon密码，在服务器配置文件里的 rcon.password= 一行 
		$timeout = 3000; // 连接超时时间

		$rcon = new Rcon($host, $port, $password, $timeout); //连接到服务器
		if ($rcon->connect()){
			$back = $rcon->send_command("stop"); //发送命令
			echo "服务器已关闭"; //输出返回信息
			$rcon->disconnect();
		}
		else{
			echo "连接失败！"; //连接失败时的信息 
		}
}