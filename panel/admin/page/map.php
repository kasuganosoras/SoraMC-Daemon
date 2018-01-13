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
function ListDir($directory){
	$mydir = dir($directory);
	$lisnum = 1;
	$getfile;
	while($file = $mydir->read()){
	    if((is_dir("$directory/$file")) && ($file!==".") && ($file!=="..")){
	        if(file_exists($directory."/".$file."/uid.dat")){
				$getfile = $getfile . "<tr><td style='height:26px;'>".$lisnum."</td><td style='height:26px;'>".iconv("GB2312","UTF-8//IGNORE",$file)."</td><td style='height:26px;'>".date("Y-m-d H:i:s",filemtime($directory.$file))."</td><td style='height:26px;'><button class='download' onclick=\"location='?s=delete&file=".iconv("GB2312","UTF-8//IGNORE",$file)."'\">删除地图</button></td><td style='height:26px;'><button class='download' onclick=\"location='?s=disable&file=".iconv("GB2312","UTF-8//IGNORE",$file)."'\">禁用地图</button></td></tr>";
				$lisnum = $lisnum + 1 ;
			}
	    }
	}
	$mydir->close();
	return Array(
		'filelist' => $getfile,
		'count' => $lisnum
		);
}
$filelist = ListDir("../../../../Minecraft/");
if(($_GET["s"]=="disable")&&($_GET["file"])){
	if(($configs["level-name"]==$_GET["file"])||($configs["level-name"]."_the_end"==$_GET["file"])){
		echo "<script>alert('无法禁用服务器默认地图！');location='map.php';</script>";
		exit;
	}
	if($version){
		include_once('rcon.php');
		$back = "";
		$host = 'localhost';
		$port = $configs["rcon.port"];
		$password = $configs["rcon.password"];
		$timeout = 3000;
		$rcon = new Rcon($host, $port, $password, $timeout);
		if ($rcon->connect()){
			$back = $rcon->send_command("stop");
			sleep(2);
			rename("../../../../Minecraft/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"]),"../../../../disable_worlds/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"]));
			$rcon->disconnect();
			echo "<script>alert('禁用地图成功！');location='map.php';</script>";
		}
		else{
			echo "<script>alert('无法连接至服务器');location='map.php';</script>";
		}
	}
	else{
		rename("../../../../Minecraft/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"]),"../../../../disable_worlds/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"]));
		echo "<script>alert('禁用地图成功！');location='map.php';</script>";
	}
}
if(($_GET["s"]=="create")&&($_GET["name"])&&($_GET["type"])){
	if(!$version){
		echo "请先启动服务器";
		exit;
	}
	switch($_GET["type"]){
		case "normal":
			$type="normal";
		break;
		case "flat":
			$type="normal -t flat";
		break;
		case "plotme":
			$type="normal -g PlotMe";
		break;
		case "nether":
			$type="nether";
		break;
		case "ender":
			$type="end";
		break;
		default:
			$type="normal";
		break;
	}
	include_once('rcon.php');
	$back = "";
	$host = 'localhost';
	$port = $configs["rcon.port"];
	$password = $configs["rcon.password"];
	$timeout = 3000;
	$rcon = @new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect()){
		$back = $rcon->send_command("mv create ".$_GET["name"]." ".$type);
		$rcon->disconnect();
		echo "创建地图成功！";
		exit;
	}
	else{
		echo "无法连接至服务器";
		exit;
	}
}
if(($_GET["s"]=="delete")&&($_GET["file"])){
	if(($configs["level-name"]==$_GET["file"])||($configs["level-name"]."_the_end"==$_GET["file"])){
		echo "<script>alert('无法删除服务器默认地图！');location='map.php';</script>";
		exit;
	}
	if($_GET["check"]=="yes"){
		if($version){
			include_once('rcon.php');
			$back = "";
			$host = 'localhost';
			$port = $configs["rcon.port"];
			$password = $configs["rcon.password"];
			$timeout = 3000;
			$rcon = new Rcon($host, $port, $password, $timeout);
			if ($rcon->connect()){
				$back = $rcon->send_command("stop");
				sleep(2);
				system("del /Q \"../../../../Minecraft/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"])."\"");
				@unlink("../../../../Minecraft/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"]));
				system("ThreadRun.exe");
				$rcon->disconnect();
				echo "<script>alert('删除地图成功！');location='map.php';</script>";
			}
			else{
				echo "<script>alert('无法连接至服务器');location='map.php';</script>";
			}
		}
		else{
			system("del /Q \"../../../../Minecraft/".iconv("UTF-8","GB2312//IGNORE",$_GET["file"])."\"");
			echo "<script>alert('删除地图成功！');location='map.php';</script>";
		}
	}
	else{
		include("model/loader.php");
		$loader = new ClassLoader();
		echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/map.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 33.33333333%;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>确认删除？</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info"><?php echo $_GET["file"]; ?></span>
						</h1>
						<br>
                        <small>您确定要删除该文件吗？</small>
						<br>
						<br>
						<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="location='?s=delete&file=<?php echo $_GET["file"]; ?>&check=yes'">确认删除</button>&nbsp;&nbsp;
						<button class="serverbtn" style="color:#FFF;background:rgba(255,0,0,0.6);" onclick="location='plugin.php'">取消删除</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?
	}
	exit;
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/map.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
	<script type="text/javascript">
		function create(){
			var name = document.getElementById('worldname').value;
			var type = document.getElementById('type').value;
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					alert(xmlhttp.responseText);
					location='map.php';
				}
			}
			xmlhttp.open("GET","?s=create&name=" + name + "&type=" + type + "&t=" + Math.random(),true);
			xmlhttp.send();
		}
	</script>
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 512px; top:18px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">地图管理器</span>
						</h1>
                        <small>服务器当前已安装 <?php echo $filelist["count"] - 1; ?> 个地图</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>地图列表</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">当前在服务器上安装的地图</h1>
						<br>
						<small>您可以自由禁用、启用、删除这些地图</small>
						<br>
						<small>快捷创建地图，可以一键创建世界</small>
						<br>
						<small>请输入地图名字，类型</small>
						<input type="text" id="worldname">
						<select id="type">
							<option value="normal">默认地形</option>
							<option value="flat">平坦地形</option>
							<option value="plotme">地皮</option>
							<option value="nether">下界</option>
							<option value="ender">末地</option>
						</select>
						<button class="create" style="color:#FFF;background:rgba(255,0,0,0.6);width:64px;border:0px;border-radius:4px;" onclick="create()">创建</button>
						<br>
						<br>
						<table class="list">
							<?php
								echo $filelist["filelist"];
							?>
						</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>