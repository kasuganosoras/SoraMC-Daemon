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
function get_config($configfilename){
	$config = Array();
    $read = file_get_contents($configfilename);
	$getline = explode("\r\n",$read);
	for($i = 0;$i < count($getline);$i++){
		$target = explode("=",$getline[$i]);
		$config[$target[0]] = $target[1];
	}
	return $config;
}
if(!file_exists("../../../../Config/JAR.ini")){
	file_put_contents("../../../../Config/jar.ini","PaperSpigot.jar");
}
if(!file_exists("../../../../Config/RAM.ini")){
	file_put_contents("../../../../Config/RAM.ini","1024");
}
if(!file_exists("../../../../Config/ServerName.ini")){
	file_put_contents("../../../../Config/ServerName.ini","NiconicoCraft");
}
$configs = get_config("../../../../Minecraft/server.properties");
function ListDir($directory){
	$mydir = dir($directory);
	$lisnum = 1;
	$jar = file_get_contents("../../../../Config/JAR.ini");
	$ram = file_get_contents("../../../../Config/RAM.ini");
	while($file = $mydir->read()){
	    if((is_dir("$directory/$file")) && ($file!==".") && ($file!=="..")){
			//Not do any things
	    }
	    else
	    if(($file !== ".") && ($file !== "..")){
			$getpoi = explode(".",$file);
			if($getpoi[count($getpoi)-1]=="jar"){
				$file = iconv("GB2312","UTF-8//IGNORE",$file);
				if($file==$jar){
					echo "<option value='".$file."' selected='selected'>".$file."</option>";
				}
				else{
					echo "<option value='".$file."'>".$file."</option>";
				}
			}
		}
	}
	$mydir->close();
}
$config_ex = file_get_contents("../Data/config_template.ini");
if($_GET["t"]){
	$maxram = $_GET["maxram"];
	$jar = $_GET["jar"];
	$server_port = $_GET["server-port"];
	$server_ip = $_GET["server-ip"];
	$rcon_port = $_GET["rcon_port"];
	$rcon_password = $_GET["rcon_password"];
	$max_players = $_GET["max-players"];
	$gamemode = $_GET["gamemode"];
	$difficulty = $_GET["difficulty"];
	$level_name = $_GET["level-name"];
	$motd = $_GET["motd"];
	$online_mode = $_GET["online-mode"];
	$hardcore = $_GET["hardcore"];
	$enable_command_block = $_GET["enable-command-block"];
	$white_list = $_GET["white-list"];
	$view_distance = $_GET["view-distance"];
	$spawn_animals = $_GET["spawn-animals"];
	$spawn_monsters = $_GET["spawn-monsters"];
	$pvp = $_GET["pvp"];
	$write_config = str_replace("%MOTD%",$motd,str_replace("%ONLINE%",$online_mode,str_replace("%RCONPASSWORD%",$rcon_password,str_replace("%RCONPORT%",$rcon_port,str_replace("%SERVERIP%",$server_ip,str_replace("%SERVERPORT%",$server_port,str_replace("%LEVELNAME%",$level_name,str_replace("%GAMEMODE%",$gamemode,str_replace("%DIFFICULTY%",$difficulty,str_replace("%SPAWNMONSTERS%",$spawn_monsters,str_replace("%PVP%",$pvp,str_replace("%HARDCORE%",$hardcore,str_replace("%ENABLECOMMANDBLOCK%",$enable_command_block,str_replace("%MAXPLAYERS%",$max_players,str_replace("%VIEWDISTANCE%",$view_distance,str_replace("%WHITELIST%",$white_list,str_replace("%SPAWNANIMALS%",$spawn_animals,$config_ex)))))))))))))))));
	file_put_contents("../../../../Minecraft/server.properties",$write_config);
	file_put_contents("../../../../Config/JAR.ini",$jar);
	file_put_contents("../../../../Config/RAM.ini",$maxram);
	file_put_contents("../../../../Config/ServerName.ini",$_GET["servername"]);
	echo "设置保存成功！";
	exit;
}
include("model/loader.php");
$loader = new ClassLoader();
echo $loader->Load("header");
?>
        <script id="jquery_172" type="text/javascript" class="library" src="../resource/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="inc_css/setting.css">
        <link rel="stylesheet" href="../assets/layer.ext.css" id="layui_layer_skinlayerextcss">
        <link rel="stylesheet" href="../assets/style.css" id="layui_layer_skinmoonstylecss">
        <script type="text/javascript" src="inc_js/setting.js"></script>
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
                                <span class="text-info">服务器信息修改</span>
                            </h1>
                            <small>核心、服务器端、插件设置</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8" style="position: absolute;top:180px;width: 1100px;">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>服务器信息修改</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins" id="status">修改服务器相关信息</h1>
                            <br>
                            <small>您可以根据自身需要修改相应配置。</small>
							<br>
							<small>右侧标有 * 的是必填/必选项。</small>
							<br>
							<small>此处设置 true 代表 是，false 代表 否</small>
                            <br>
                            <br>
                            <table class="list">
                                <tr>
                                    <td style="width:30%;">
                                        <span>最大内存，单位 MB *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="maxram" value="<?php echo file_get_contents("../../../../Config/RAM.ini"); ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>JAR文件，从右侧选择 *</span>
                                    </td>
                                    <td>
                                        <select id="jar">
                                            <?php ListDir("../../../../JAR/"); ?>
                                        </select>
                                </tr>
                                <tr>
                                    <td>
                                        <span>服务器端口，允许0~65535 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="server_port" value="<?php echo $configs["server-port"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>服务器地址，可以不需要填写</span>
                                    </td>
                                    <td>
                                        <input type="text" id="server_ip" value="<?php echo $configs["server-ip"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>Rcon通讯端口，用于控制台与服务器通讯 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="rcon_port" value="<?php echo $configs["rcon.port"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>Rcon通讯密码，用于确认控制台的身份 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="rcon_password" value="<?php echo $configs["rcon.password"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>最大在线玩家，可以自由填写大小 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="max_players" value="<?php echo $configs["max-players"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>默认游戏模式，0生存，1创造，2冒险 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="gamemode" value="<?php echo $configs["gamemode"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>游戏难度，0和平，1简单，2普通，3困难 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="difficulty" value="<?php echo $configs["difficulty"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>主世界名字，默认为 world *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="level_name" value="<?php echo $configs["level-name"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>Motd标语介绍，显示在服务器列表中，中文要转码</span>
                                    </td>
                                    <td>
                                        <input type="text" id="motd" value="<?php echo $configs["motd"]; ?>" style="width:89.4%;">
										<button id="encode">转码</button>
                                </tr>
                                <tr>
                                    <td>
                                        <span>正版验证，如果是离线服请选择false *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="online_mode" value="<?php echo $configs["online-mode"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>困难模式，死亡后会被封禁 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="hardcore" value="<?php echo $configs["hardcore"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>启用命令方块，不启用将无法使用命令方块 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="enable_command_block" value="<?php echo $configs["enable-command-block"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>白名单模式，开启后只有在白名单内的玩家才能进入 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="white_list" value="<?php echo $configs["white-list"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>最大视距，降低此处可以增加服务器性能 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="view_distance" value="<?php echo $configs["view-distance"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>生成动物，禁用后将不会生成动物 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="spawn_animals" value="<?php echo $configs["spawn-animals"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>生成怪物，禁用后将不会生成怪物 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="spawn_monsters" value="<?php echo $configs["spawn-monsters"]; ?>">
                                </tr>
                                <tr>
                                    <td>
                                        <span>玩家之间PVP，禁用后玩家无法互相攻击 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="pvp" value="<?php echo $configs["pvp"]; ?>">
                                </tr>
								<tr>
                                    <td>
                                        <span>服务器名字，显示在网页上 *</span>
                                    </td>
                                    <td>
                                        <input type="text" id="servername" value="<?php echo file_get_contents("../../../../Config/ServerName.ini"); ?>">
                                </tr>
                            </table>
                            <br>
                            <center>
                                <button class="download" onclick="save()">保存设置</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>$(function () {
                var atou = function (str) {
                    var res = "";
                    if ($("#conf-ascii").prop("checked")) {
                        for (var i = 0; i < str.length; i++) {
                            res += "\\u" + ("00" + str.charCodeAt(i).toString(16)).slice(-4);
                        }
                    } else {
                        for (var i = 0; i < str.length; i++) {
                            var sc = str.charCodeAt(i);
                            if (sc <= 127)
                                res += str[i];
                            else
                                res += "\\u" + ("00" + sc.toString(16)).slice(-4);
                        }
                    }
                    return res;
                };
                var utoa = function (str) {
                    str = str.replace(/\\u/g, "%u");
                    return unescape(str);
                };

                $("#encode").click(function () {
                    $("#motd").val(atou($("#motd").val()));
                });

                var changeevi = function () {
                    setTimeout(function () {
                        if ($("#conf-auto").prop("checked"))
                            $("#motd").val(atou($("#motd").val()));
                    }, 1);
                };
            });</script>
    </body>
</html>