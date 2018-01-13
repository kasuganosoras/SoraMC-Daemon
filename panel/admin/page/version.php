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
$Ver = file_get_contents("../../../../Config/JAR.ini");
if(!$_SESSION["adminuser"]){
	echo "<script>location='login.php';</script>";
	exit;
}
if(($_GET["s"]=='delete')&&($_GET["file"])){
	unlink("../../../../JAR/".iconv("GB2312","UTF-8//IGNORE",$_GET["file"]));
	echo "\"".iconv("GB2312","UTF-8//IGNORE",$_GET["file"])."\" 已删除！";
	exit;
}
function ListDir($directory){
	$mydir = dir($directory);
	$lisnum = 1;
	while($file = $mydir->read()){
	    if((is_dir("$directory/$file")) && ($file!==".") && ($file!=="..")){
	        //Not do any things
	    }
	    else
	    if(($file !== ".") && ($file !== "..")){
			$size = filesize($directory.$file) / 1048576 ;
			$getpoi = explode(".",$size);
			echo "<tr><td class='listnum'>".$lisnum."</td><td>$file</td><td>".$getpoi[0].".".substr($getpoi[1],0,3)."M</td><td>".date("Y-m-d H:i:s",filemtime($directory.$file))."</td><td><button class='download' onclick=\"deletef('".$file."')\">删除版本</button></td></tr>";
			$lisnum = $lisnum + 1 ;
		}
	}
	$mydir->close();
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/version.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
	<script type="text/javascript" src="inc_js/version.js"></script>
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: absolute;width: 33.33333333%;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务器信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">版本管理</span>
						</h1>
                        <small>游戏版本管理</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>管理服务端</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">管理Minecraft服务端</h1>
						<br>
						<small>当前版本：<?php echo $Ver; ?></small>
						<br>
						<br>
						<?php
						echo "<table class='versionlist'><tr><th>序号</th><th>版本</th><th>大小</th><th>更新时间</th><th>删除</th></tr>";
						ListDir("../../../../JAR/");
						echo "</table>";
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>