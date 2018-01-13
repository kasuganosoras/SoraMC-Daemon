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
if(($_GET["user"])&&($_GET["pass"])&&($_GET["servername"])){
	file_put_contents("../../../../Config/ServerName.ini",$_GET["servername"]);
	file_put_contents("../data/user.php","<?php
\$username = \"".$_GET["user"]."\";
\$password = \"".sha1(md5($_GET["pass"]))."\";");
	echo "修改成功！";
	exit;
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
	<link rel="stylesheet" href="inc_css/info.css">
	<link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
	<script type="text/javascript" src="inc_js/info.js?s=123"></script>
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
						<span class="text-info">修改信息</span>
						</h1>
                        <small>修改您的用户数据</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" style="position: absolute;top:180px;width: 1024px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>修改用户信息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" id="status">编辑您的服务器名字、账号密码</h1>
						<small>更多设置请访问 管理器 > 服务器设置</small>
						<br>
						<br>
						<table style="width:100%;text-align:center;">
							<tr>
								<td>服务器名</td>
								<td>
									<input type="text" id="servername" value="<?php echo file_get_contents("../../../../Config/ServerName.ini"); ?>"></input>
								</td>
							</tr>
							<?php include("../data/user.php"); ?>
							<tr>
								<td>管理账号</td>
								<td>
									<input type="text" id="user" value="<?php echo $username; ?>"></input>
								</td>
							</tr>
							<tr>
								<td>管理密码</td>
								<td>
									<input type="password" id="pass"></input>
								</td>
							</tr>
						</table>
						<br>
						<center>
							<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="submit()">保存数据</button>
						</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>