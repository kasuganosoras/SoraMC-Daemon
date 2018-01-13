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
if($_GET["s"]=="logout"){
	unset($_SESSION["adminuser"]);
	echo "<script>top.location='http://userlogout:nopassword@".$_SERVER["HTTP_HOST"]."/admin/login.php?s=logout';</script>";
}
if(!file_exists('../../../Minecraft/plugins/')){
	mkdir('../../../Minecraft/plugins/');
}
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPMC 3 网页管理Minecraft服务器系统</title>
    <meta name="keywords" content="&lt;block name=&#39;keywords&#39;&gt;">
    <meta name="description" content="&lt;block name=&#39;description&#39;&gt;">
    <link rel="shortcut icon" href="favicon.ico?s=123">
    <link href="./assets/bootstrap.min14ed.css" rel="stylesheet">
    <link href="./assets/font-awesome.min93e3.css" rel="stylesheet">
    <link href="./assets/animate.min.css" rel="stylesheet">
    <link href="./assets/style.min862f.css" rel="stylesheet">
    <script src="./assets/jquery.min.js"></script>
    <script src="./assets/bootstrap.min.js"></script>
    <script src="./assets/jquery.metisMenu.js"></script>
    <script src="./assets/jquery.slimscroll.min.js"></script>
    <script src="./assets/layer.min.js"></script><link rel="stylesheet" href="./assets/layer.css" id="layui_layer_skinlayercss">
    <script src="./assets/hplus.min.js"></script>
    <script src="./assets/contabs.min.js"></script>
	<script src="./assets/updatecheck.js?s=12"></script>
	<script type="text/javascript">
		getnewversion("<?php echo file_get_contents("data/version.dat"); ?>");
		showinfo("欢迎使用 PHPMC 3 管理器","有任何问题记得向作者反馈哦！");
	</script>
<link rel="stylesheet" href="./assets/layer.ext.css" id="layui_layer_skinlayerextcss"><link rel="stylesheet" href="./assets/style.css" id="layui_layer_skinmoonstylecss"></head>

<body class="fixed-sidebar full-height-layout gray-bg">
    
	<div id="wrapper">
		<!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;"><div class="sidebar-collapse" style="width: auto; height: 100%;">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element" style="width:256px;">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="" style="position: relative;left: -10px;">
						<table style="width:100%;">
							<td>
								<img src="favicon.ico" style="width:32px;height:32px;">
							</td>
							<td>
								<span class="clear">
									<span class="block m-t-xs"><strong class="font-bold">您好，<?php echo $_SESSION["adminuser"]; ?></strong></span>
									<span class="text-muted text-xs block">欢迎使用 PHPMC 3 后台</span>
								</span>
							</td>
						</table>
                    </a>
                </div>
            </li>
            <li>
                <a class="J_menuItem" href="page/main.php" data-index="0" onclick="document.getElementById('frame1').style.display=''">
					<img src="./assets/images/home.png" style="width:18px;height:18px;">&nbsp;&nbsp;
					<span class="nav-label">主页</span>
				</a>
            </li>
       

            <li>
                <a href="">
                    <!--<i class="fa fa-user"></i>-->
					<img src="./assets/images/info.png" style="width:18px;height:18px;">&nbsp;&nbsp;
                    <span class="nav-label">服务器信息</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                    <li>
                        <a class="J_menuItem" href="page/main.php" data-index="0" onclick="document.getElementById('frame1').style.display=''">服务器信息</a>
                        <a class="J_menuItem" href="page/info.php" data-index="0">修改信息</a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="J_menuItem" href="page/console.php" data-index="3"><img src="./assets/images/console.png" style="width:18px;height:18px;">&nbsp;&nbsp;&nbsp;<span class="nav-label">控制台</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="page/chat.php" data-index="4"><img src="./assets/images/chat.png" style="width:18px;height:18px;">&nbsp;&nbsp;&nbsp;<span class="nav-label">聊天栏</span></a>
            </li>


            <li>
                <a href="#">
                    <img src="./assets/images/manager.png" style="width:18px;height:18px;">&nbsp;&nbsp;
                    <span class="nav-label">管理器</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                    <li>
						<a class="J_menuItem" href="page/version.php" data-index="0">版本管理</a>
						<a class="J_menuItem" href="page/download.php" data-index="0">版本下载</a>
                        <a class="J_menuItem" href="page/plugin.php" data-index="0">插件管理</a>
                        <a class="J_menuItem" href="page/map.php" data-index="0">地图管理</a>
                        <a class="J_menuItem" href="page/setting.php" data-index="0">服务器设置</a>
						<a class="J_menuItem" href="page/update.php" data-index="0">更新系统</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 4px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 971px;"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.9; z-index: 90; right: 1px;"></div></div>
</nav>
<!--左侧导航结束-->
<!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a class="active J_menuTab" data-id="page/main.php" onclick="document.getElementById('frame1').style.display='';">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll" onclick="document.getElementById('frame1').style.display=''"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="?s=logout" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe0" id="frame1" width="100%" height="100%" src="page/main.php" frameborder="0" data-id="/user/main" seamless=""></iframe>
            </div>
            <div class="footer">
                <div class="pull-right">© 2012-2017 <a href="http://niconicocraft.com/" target="_blank">Niconico Craft 提供技术支持</a>
                </div>
            </div>
        </div>
        <!--右侧部分结束-->
	</div>
</body></html>