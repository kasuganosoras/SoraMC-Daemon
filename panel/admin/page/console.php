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
	echo "<script>location='login.php';</script>";
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
function NiconicoCraft_Query($IP, $Port = 25565,$Timeout = 1){
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
$array = NiconicoCraft_Query($mcserverip ,$mcserverport);
$name = $array['HostName'];
$max = $array['MaxPlayers'];
$online = $array['Players'];
$version = $array['Version'];
if($_GET["command"]){
	if(!$_SESSION["adminuser"]){
		exit;
	}
	if(!$version){
		echo "服务器当前离线";
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
		$back = $rcon->send_command($_GET["command"]); //发送命令
		echo $back; //输出返回信息
		$rcon->disconnect();
	}
	else{
		echo "连接失败！"; //连接失败时的信息 
	}
}
elseif($_GET["s"]=="logs"){
	if(!file_exists("../../../../Minecraft/logs.temp")){
		exit;
	}
	include("model/replace.php");
	$replace = new Replace();
	echo $replace->console(file_get_contents("../../../../Minecraft/logs.temp"));
	exit;
}
else{
	if(!$_SESSION["adminuser"]){
		exit;
	}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/console.css?s=1">
	<script type="text/javascript" src="inc_js/console.js?s=12"></script>
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
    
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 512px;top: 18px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器控制</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">服务器控制台</span>
						</h1>
						<br>
                        <button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="startserver()">开启服务器</button>&nbsp;&nbsp;
						<button class="serverbtn" style="color:#FFF;background:rgba(255,0,0,0.6);" onclick="stopserver()">关闭服务器</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器状态/命令操作</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">Loading...</h1>
						<br>
						<small>您可以在这里执行后台指令</small>
						<br>
						<br>
						<div contenteditable="true" id="logs" readonly="readonly"></div>
						<input id="command" clientidmode="Static"></input>
						<input type="button" id="runcmd" onclick="runcmd()" value="执行"></input>
						<br>
						<br>
						<div class="toolbox">
							<select id="type">
								<option value="gamemode 1 ">设置玩家游戏模式为创造</option>
								<option value="gamemode 0 ">设置玩家游戏模式为生存</option>
								<option value="gamemode 2 ">设置玩家游戏模式为冒险</option>
								<option value="op ">给予目标玩家管理员权限</option>
								<option value="deop ">取消目标玩家管理员权限</option>
								<option value="kill ">杀死指定的玩家</option>
								<option value="heal ">治疗指定的玩家</option>
								<option value="kick ">踢出指定的玩家</option>
								<option value="mute ">禁言指定的玩家</option>
								<option value="ban ">封禁指定的玩家</option>
								<option value="unban ">解禁指定的玩家</option>
							</select>
							<input type="text" class="playername" id="playername" placeholder=" 指定的玩家游戏名"></input>
							<input type="button" class="doit" onclick="doit()" value="执行"></input>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?
}
?>