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
if(!file_exists("../../../../Minecraft/plugins/")){
	mkdir("../../../../Minecraft/plugins");
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
function ListDir2($directory){
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
$filelist2 = ListDir2("../../../../Minecraft/");
function ListDir($directory){
	$mydir = dir($directory);
	$lisnum = 1;
	$getfile;
	while($file = $mydir->read()){
	    if((is_dir("$directory/$file")) && ($file!==".") && ($file!=="..")){
			//Not do any things
	    }
	    else
	    if(($file !== ".") && ($file !== "..")){
			$getpoi = explode(".",$file);
			if($getpoi[1]=="jar"){
				$getfile = $getfile . "<tr><td>".$lisnum."</td><td>$file</td><td>".date("Y-m-d H:i:s",filemtime($directory.$file))."</td><td><button class='download' onclick=\"location='?s=delete&file=".$file."'\">删除插件</button></td><td><button class='download' onclick=\"location='?s=delete&file=".$file."'\">禁用插件</button></td></tr>";
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
$filelist = ListDir("../../../../Minecraft/plugins/");
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/main.css">
	<script type="text/javascript" src="inc_js/main.js"></script>
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
    
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 512px;top:18px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>系统信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">NiconicoCraft PHPMC</span>
						</h1>
                        <small>版本：3.0.1.4&nbsp;&nbsp;&nbsp;&nbsp;已安装：<?php echo file_get_contents("../data/system.dat"); ?> 个补丁</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" style="position: absolute;margin-left: 512px;width: 512px;top:18px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>在线玩家</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="online"><span class="text-info">Loading...</span></h1>
                        <small>在线 / 总共</small>
                    </div>
                </div>
            </div>
			<div class="col-lg-4" style="position: absolute;top:175px;width: 512px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>游戏信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            <span class="text-info"><?php echo $filelist["count"] -1; ?></span>
                            /
                            <span class="text-danger"><?php echo $filelist2["count"] -1; ?></span>
                        </h1>
                        <small>插件 / 地图</small>
                    </div>
                </div>
            </div>
			<div class="col-lg-4" style="position: absolute;top: 175px;margin-left: 512px;width: 512px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器时间</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            <span class="text-info"><?php echo date("Y-m-d h:i:s"); ?></span>
                        </h1>
                        <small>当前服务器上的时间</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:330px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器状态</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">Loading...</h1>
						<br>
						<small>选择一个操作来控制您的服务器</small>
						<br>
						<br>
						<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="startserver()">开启服务器</button>&nbsp;&nbsp;<button class="serverbtn" style="color:#FFF;background:rgba(255,0,0,0.6);" onclick="stopserver()">关闭服务器</button>
						<br>
						<br>
						<small>关服后自动重启</small>
						<select id="autorestart">
							<option value="false">否</option>
							<option value="true">是</option>
						</select>
					</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>