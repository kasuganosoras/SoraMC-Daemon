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
if(($_GET["s"]=='download')&&($_GET["version"])&&($_GET["file"])){
	$download = file_get_contents("http://cdn.tcotp.cn/download/server/".$_GET["version"]."/".$_GET["file"]) or die ("\"".$_GET["file"]."\" 下载失败！");
	file_put_contents("../../../../JAR/".$_GET["file"],$download);
	file_put_contents("../../../../Config/JAR.ini",$_GET["file"]);
	echo "\"".$_GET["file"]."\" 下载完成！";
	exit;
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/download.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
	<script type="text/javascript" src="inc_js/download.js"></script>
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
						<span class="text-info">版本下载</span>
						</h1>
                        <small>游戏版本下载</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>下载服务端</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">下载Minecraft服务端</h1>
						<br>
						<small>当前版本：<?php echo $Ver; ?></small>
						<br>
						<br>
						<select id="type">
							<?php
							$versionlist = base64_decode(file_get_contents("http://temp.tcotp.cn/phpmc/download/"));
							$split = explode("/",$versionlist);
							for($i = 0 ;$i < count($split);$i++){
								echo "<option onclick=\"showVersion()\">".$split[$i]."</option>";
							}
							?>
						</select>
						<input type="button" value="确定" class="loadbtn" onclick="showVersion()"></input>&nbsp;&nbsp;
						<small id="dstatus"></small>
						<div id="show"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>