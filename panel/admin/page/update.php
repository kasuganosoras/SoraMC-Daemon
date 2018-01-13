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
$Ver = file_get_contents("../data/version.dat");
if(!$_SESSION["adminuser"]){
	echo "<script>location='login.php';</script>";
	exit;
}
if($_GET["s"]=='update'){
	$newver = file_get_contents("http://temp.tcotp.cn/phpmc/version/?ver=".$Ver);
	if($newver==$Ver){
		echo 'new';
		exit;
	}
	else{
		file_put_contents('../../../../update_temp.exe',file_get_contents("http://cdn.tcotp.cn/download/PHPMC/Version/".$newver.".exe"));
		passthru('cd ../../../../&update_temp.exe');
		unlink('../../../../update_temp.exe');
		file_put_contents("../data/version.dat",$newver);
		file_put_contents("../data/system.dat",file_get_contents("../system.dat") + 1);
		echo 'ok';
		exit;
	}
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/update.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
	<script type="text/javascript" src="inc_js/update.js?s=123"></script>
	<script type="text/javascript">
		getversion("<?php echo $Ver; ?>");
	</script>
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
						<span class="text-info">系统更新</span>
						</h1>
                        <small>更新软件系统</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>系统组件更新</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">将您的系统更新到新版本</h1>
						<br>
						<small>当前版本：Ver.3.0.<?php echo $Ver; ?></small>
						<br>
						<br>
						<small>最新版本：<v id="version">获取中...</v></small>
						<br>
						<br>
						<small id="text">&nbsp;</small>
						<br>
						<center>
							<button class="serverbtn" id='updatebtn' style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="update()">立即更新</button>
						</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>