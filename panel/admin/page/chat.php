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
/**
 *
 * 读取服务器设置信息
 *
 */
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
/**
 *
 * Socket 发送数据到服务器
 *
 */
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
/**
 *
 * Socket 发送数据到服务器
 *
 */
if($_GET["command"]){
	if(!$_SESSION["adminuser"]){
		exit;
	}
	if(!$version){
		echo "服务器当前离线";
		exit;
	}
	include_once('rcon.php');
	$back = "";

	$host = 'localhost';
	$port = $configs["rcon.port"];
	$password = $configs["rcon.password"];
	$timeout = 3000;

	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect()){
		$back = $rcon->send_command($_GET["command"]);
		echo $back;
		$rcon->disconnect();
	}
	else{
		echo "连接失败！";
	}
}
elseif($_GET["s"]=="logs"){
	if(!file_exists("../../../../Minecraft/logs.temp")){
		exit;
	}
	/**
	 *
	 * 控制台汉化，字体着色
	 *
	 */
	include("model/replace.php");
	$replace = new Replace();
	echo $replace->chat(file_get_contents("../../../../Minecraft/logs.temp"));
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
	<link rel="stylesheet" href="inc_css/chat.css">
	<script type="text/javascript" src="inc_js/chat.js"></script>
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
    
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 512px;top:18px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Chat</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">服务器控制</span>
						</h1>
						<br>
                        <button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="startserver()">开启服务器</button>&nbsp;&nbsp;<button class="serverbtn" style="color:#FFF;background:rgba(255,0,0,0.6);" onclick="stopserver()">关闭服务器</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 66.666666666%;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>聊天内容</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">Loading...</h1>
						<br>
						<small>您可以在这里快捷发送聊天内容，不需要 /</small>
						<br>
						<br>
						<div contenteditable="true" id="logs" readonly="readonly"></div>
						<input id="command" clientidmode="Static"></input>
						<input type="button" id="runcmd" onclick="runcmd()" value="发送"></input>
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