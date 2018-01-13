<?php
if(file_exists("admin/data/install.lock")){
	echo "<script>alert('系统已经安装过了！不能再次安装！\\n重装请删除 admin/data/install.lock 文件');location='./';</script>";
	exit;
}
if($_GET["step"]=="2"){ //($_GET["maxram"])&&($_GET["user"])&&($_GET["pass"])
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPMC 3 网页管理Minecraft服务器系统</title>
    <meta name="keywords" content="&lt;block name=&#39;keywords&#39;&gt;">
    <meta name="description" content="&lt;block name=&#39;description&#39;&gt;">
    <link rel="shortcut icon" href="favicon.ico">
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
            <div class="col-lg-4" style="position: fixed;width: 33.33333333%;left:25%;" id="content">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Niconico Craft PHPMC</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">安装向导</span>
						</h1>
						<br>
						<small>许可协议 > 安装设置</small>
						<br>
						<br>
						<form>
							<input type="hidden" name="step" value="3" ></input>
							<table style="width:100%;text-align:center;">
								<tr>
									<td>服务器名</td>
									<td>
										<input type="text" name="servername"></input>
									</td>
								</tr>
								<tr>
									<td>最大内存</td>
									<td>
										<input type="text" name="maxram" value="1024"></input>
									</td>
								</tr>
								<tr>
									<td>管理账号</td>
									<td>
										<input type="text" name="user"></input>
									</td>
								</tr>
								<tr>
									<td>管理密码</td>
									<td>
										<input type="password" name="pass"></input>
									</td>
								</tr>
							</table>
							<br>
							<center>
								<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" type="submit">开始安装</button>
							</center>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		function change(){
			var view1 = document.body.clientWidth;
			var view2 = document.body.clientHeight;
			var now1 = view1 - 636 ;
			var now2 = view2 - 368;
			var now12 = now1 / 2 ;
			var now22 = now2 / 2 ;
			document.getElementById('content').style.left = now12+'px';
			document.getElementById('content').style.top = now22+'px';
		}
		setInterval(change,1);
		</script>
</body>
</html>
<?
}
elseif($_GET["step"]=="3"){
	if(($_GET["servername"])&&($_GET["maxram"])&&($_GET["user"])&&($_GET["pass"])){
		file_put_contents("../../Config/ServerName.ini",$_GET["servername"]);
		file_put_contents("../../Config/RAM.ini",$_GET["maxram"]);
		file_put_contents("admin/data/install.lock","如果您想要重装本系统，请删除此文件。");
		file_put_contents("admin/data/user.php","<?php
\$username = \"".$_GET["user"]."\";
\$password = \"".sha1(md5($_GET["pass"]))."\";");
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPMC 3 网页管理Minecraft服务器系统</title>
    <meta name="keywords" content="&lt;block name=&#39;keywords&#39;&gt;">
    <meta name="description" content="&lt;block name=&#39;description&#39;&gt;">
    <link rel="shortcut icon" href="favicon.ico">
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
            <div class="col-lg-4" style="position: fixed;width: 33.33333333%;left:25%;" id="content">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Niconico Craft PHPMC</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">安装向导</span>
						</h1>
						<br>
						<small>许可协议 > 安装设置 > 安装完成</small>
						<br>
						<br>
						<span>安装完成！您现在可以登陆系统了！</span>
						<br>
						<br>
						<span>感谢使用，如果遇到任何问题，欢迎向我反馈！</span>
						<br>
						<br>
						<center>
							<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="location='admin/'">完成</button>
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
			var now1 = view1 - 636 ;
			var now2 = view2 - 368;
			var now12 = now1 / 2 ;
			var now22 = now2 / 2 ;
			document.getElementById('content').style.left = now12+'px';
			document.getElementById('content').style.top = now22+'px';
		}
		setInterval(change,1);
		</script>
</body>
</html>
<?
	}
	else{
		echo "<script>alert('抱歉，您还有一些项目没有填写，请填写完整');location='?step=2';</script>";
		exit;
	}
}
else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPMC 3 网页管理Minecraft服务器系统</title>
    <meta name="keywords" content="&lt;block name=&#39;keywords&#39;&gt;">
    <meta name="description" content="&lt;block name=&#39;description&#39;&gt;">
    <link rel="shortcut icon" href="favicon.ico">
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
	</style>
	<link rel="stylesheet" href="admin/assets/layer.ext.css" id="layui_layer_skinlayerextcss">
	<link rel="stylesheet" href="admin/assets/style.css" id="layui_layer_skinmoonstylecss">
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
	<div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4" style="position: fixed;width: 33.33333333%;left:25%;" id="content">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Niconico Craft PHPMC</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
						<span class="text-info">安装向导</span>
						</h1>
						<br>
						<small>许可协议</small>
						<br>
						<br>
						<textarea class="lic" readonly="readonly"><?php echo file_get_contents("admin/data/lic.txt"); ?></textarea>
						<br>
                        <center>
							<button class="serverbtn" style="color:#FFF;background:rgba(0,173,255,0.8);" onclick="location='?step=2'">接受许可协议</button>
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
			var now1 = view1 - 636 ;
			var now2 = view2 - 483;
			var now12 = now1 / 2 ;
			var now22 = now2 / 2 ;
			document.getElementById('content').style.left = now12+'px';
			document.getElementById('content').style.top = now22+'px';
		}
		setInterval(change,1);
		</script>
</body>
</html>
<?
}