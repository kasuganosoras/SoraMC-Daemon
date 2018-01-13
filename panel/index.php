<?php
if(!file_exists("admin/data/install.lock")){
	echo "<script>location='install.php';</script>";
	exit;
}
$ServerName = file_get_contents('../../Config/ServerName.ini');
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
$configs = get_config("../../Minecraft/server.properties");
$mcserverip = 'localhost';
$mcserverport = $configs["server-port"];
$array = NiconicoCraft_Query($mcserverip ,$mcserverport);
$name = $array['HostName'];
$max = $array['MaxPlayers'];
$online = $array['Players'];
$version = $array['Version'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $ServerName; ?> | Server Status - Powered by PHPMC</title>
    <meta name="keywords" content="&lt;block name=&#39;keywords&#39;&gt;">
    <meta name="description" content="&lt;block name=&#39;description&#39;&gt;">
    <link rel="shortcut icon" href="admin/favicon.ico">
    <link href="admin/assets/bootstrap.min14ed.css" rel="stylesheet">
    <link href="admin/assets/font-awesome.min93e3.css" rel="stylesheet">
    <link href="admin/assets/animate.min.css" rel="stylesheet">
    <link href="admin/assets/style.min862f.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script src="admin/assets/jquery.min.js"></script>
    <script src="admin/assets/bootstrap.min.js"></script>
    <script src="admin/assets/jquery.metisMenu.js"></script>
    <script src="admin/assets/jquery.slimscroll.min.js"></script>
    <script src="admin/assets/layer.min.js"></script>
	<link rel="stylesheet" href="admin/assets/layer.css" id="layui_layer_skinlayercss">
    <script src="admin/assets/hplus.min.js"></script>
    <script src="admin/assets/contabs.min.js"></script>
	<style type="text/css">
		.serverbtn{
			width:128px;
			height:26px;
			border:0px;
			border-radius:4px;
			font-size:14px;
		}
		.lic{
			width:100%;
			height:256px;
			border:1px solid #d6d6d6;
			border-radius:4px;
			margin:auto;
			overflow:auto;
		}
		table tr td input{
            width:80%;
            height:24px;
            border:1px solid rgba(0,0,0,0.3);
            border-radius:4px;
			text-align:center;
        }
		tr{
			height:32px;
		}
	</style>
	<link rel="stylesheet" href="admin/assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="admin/assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: fixed;width: 456px;height:258px;left:25%;" id="content">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info"><?php echo $ServerName; ?></span>
						</h1>
						<br>
						<small style='font-size:22px;'><?php if($version){echo '当前在线: '.$online.'/'.$max;}else{echo '服务器当前离线';} ?></small>
						<br>
						<br>
						<br>
						<center>
							<span>Powered by <a href='http://niconicocraft.com/'>NiconicoCraft PHPMC 3.0</a></span>
						</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		function change(){
			var view1 = document.body.clientWidth;
			var view2 = document.body.clientHeight;
			var now1 = view1 - 426 ;
			var now2 = view2 - 220;
			var now12 = now1 / 2 ;
			var now22 = now2 / 2 ;
			document.getElementById('content').style.left = now12+'px';
			document.getElementById('content').style.top = now22+'px';
		}
		setInterval(change,1);
		showinfo("欢迎使用PHPMC 3管理系统","Minecraft服务器状态页面由PHPMC 3生成");
	</script>
</body>
</html>