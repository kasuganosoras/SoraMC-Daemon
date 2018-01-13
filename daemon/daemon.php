<?php
// DEBUG 用，正式上线时删除
error_reporting(E_ALL);
/**
 *
 *	SoraMC Server Daemon Reloaded
 *
 *	本程序使用 GNU GPL v3 协议开源
 *
 *	使用时请遵守协议 ( LICENSE )
 *  dhdj reloaded
 **/

// 引入加密类和工具类，载入配置文件
include("AES.php");
include("tools.php");
include("../config/config.php");
// 读入配置文件
$keys = $aesenkey;
$port = $bindport;
$host = $bindhost;
$auth = $contoken;
$mrys = $httpmrys;
$hprt = $httpport;
// Tools 工具类
$tools = new Tools();
echo '    _  __                                         ____                  \n';
echo '   | |/ /__ _ ___ _   _  __ _  __ _ _ __   ___   / ___|  ___  _ __ __ _ \n';
echo '   | " // _` / __| | | |/ _` |/ _` | "_ \ / _ \  \___ \ / _ \| "__/ _` |\n';
echo '   | . \ (_| \__ \ |_| | (_| | (_| | | | | (_) |  ___) | (_) | | | (_| |\n';
echo '   |_|\_\__,_|___/\__,_|\__, |\__,_|_| |_|\___/  |____/ \___/|_|  \__,_|\n';
echo '                        |___/                                           \n';
echo '                                                  Minecraft Server Panel\n';
echo '                                          Reloaded by dhdj[William Wang]\n';
// 创建 Socket
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(@socket_bind($socket, $host, $port) === false) {
	$tools->println("**** FAILED TO BIND TO PORT!", false, 2);
	$tools->println("Perhaps a server is already running on that port?", false, 2);
	exit;
}
@socket_listen($socket, 5);
$tools->println("SoraMCReloaded Version ".$tools->getSoraMC("version"));
$tools->println("Daemon Running on port: " . $port);
sleep(1);
$tools->println("Starting httpd service ...");
$httpdthread = new HttpServer($hprt, $mrys, $auth . " sora.log >test.log");
$httpdthread->start();
usleep(500);
$tools->println("Delete old log file ...");
@file_put_contents("command.dat", "");
@unlink("./Minecraft/logs/latest.log");
@unlink("./sora.log");
@unlink("./status.dat");
usleep(500);
$tools->println("Starting log service ...");
$logthread = new logs("");
$logthread->start();
// 创建 Minecraft 服务端文件夹
if(!file_exists("./Minecraft/")) {
	$tools->println("Minecraft server folder not found, try to create it ...");
	if(@mkdir("./Minecraft/", 0777)) {
		$tools->println("Successful create Minecraft server folder");
	} else {
		$tools->println("Failed to create Minecraft server folder, Perhaps you don't have enough Permission(s)");
	}
}
while(true) {
	// 接受来自客户端的连接
    $connect = socket_accept($socket);
    if($connect !== false){
		// 读入客户端发送的数据，缓冲区设置成 8192 一般是足够的了
        while($read = @socket_read($connect, 8192)){
			// 得到客户端信息
			socket_getpeername($connect, $clientip, $clientport);
			// 创建 AES 对象
			$aes = new AES($bit = 256, $key = md5($keys), $iv = md5($keys), $mode = 'cfb');
			/**
			 *
			 *	判断客户端发送过来的内容，如果符合协议编码规范即 Base64，则接受连接
			 *
			 **/
			if(preg_match("/^[A-Za-z0-9\=\/\+]+$/", $read)) {
				$tools->println("New request from socket: " . $clientip . ":" . $clientport); // DEBUG
				$decrypt = $aes->decrypt($read); // 解密数据
				$action = json_decode($decrypt, true);
				if($action["action"] == "login") {
					if($action["key"] == $keys) {
						$res = $tools->status(200, $auth);
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // 认证成功，返回 token
					} else {
						$res = $tools->status(403, 'Auth Failed');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // 认证失败，返回 Auth Failed
					}
				} else {
					if($action["token"] == $keys) {
						switch($action["action"]) {
							case "start_mac":
								if(file_exists("status.dat")) {
									$res = $tools->status(502, 'Server Is Running');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 服务器已经在运行
								} else {
									@file_put_contents("status.dat", "");
									$thread = new Minecraft("start_mac"); //启动 Minecraft 服务端
									$thread->start();
									$res = $tools->status(200, 'Successful Start Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 成功启动服务器
								}
								break;
							case "stop":
								if(file_exists("status.dat")) {
									@file_put_contents(/** @lang text */
                                        "command.dat", "stop");
									$res = $tools->status(200, 'Successful Stop Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 成功关闭服务端
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 服务器已经停止了
								}
								break;
							case "restart":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "stop");
									$restart = new reStart("start_mac");
									$restart->start();
									$res = $tools->status(200, 'Successful reStart Server');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // 成功重启服务端
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 服务端未运行
								}
								break;
							case "status":
								if(file_exists("status.dat")) {
									$res = $tools->status(200, 'Server Online');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // 服务器在线
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 服务器离线
								}
								break;
							case "daemonversion":
								$res = $tools->status(403, $tools->getSoraMC("version"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // Daemon 版本
								break;
							case "daemonencrypt":
								$res = $tools->status(403, $tools->getSoraMC("encrypt"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // Daemon 加密方式
								break;
							case "getserverconfig":
								$res = $tools->status(403, $tools->getSoraMC("config"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // 获取服务端设置
								break;
							case "getsystemconfig":
								$res = $tools->status(403, $tools->getSystemConfig());
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // 获取 Daemon 设置
								break;
							case "saveconfig":
								@file_put_contents("./Minecraft/server.properties", base64_decode($action["args"]));
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // 保存服务端设置
								break;
							case "savesystemconfig":
								$sconf = json_decode(base64_decode($action["args"]), true);
								$tools->saveSystemConfig($sconf["corename"], $sconf["jvmmaxmr"], $sconf["javapath"]);
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // 保存 Daemon 设置
								break;
							case "sendcommand":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									$res = $tools->status(200, 'Successful Run Command');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 成功发送命令
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // 服务端不在线
								}
								break;
							case "sendmessage":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "say " . iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									@file_put_contents("./Minecraft/playerchat.log", "[" . date("H:i:s") . "]<Server> " . iconv("UTF-8", "GB2312", base64_decode($action["args"])) . "\n", FILE_APPEND);
									$res = $tools->status(200, 'Successful Send Message');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // 成功发送消息
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // 服务端不在线
								}
								break;
							default:
								$res = $tools->status(404, 'Action Not Found');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // 操作不存在
						}
					} else {
						$res = $tools->status(403, 'Token Forbidden');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // Token 不正确
					}
				}
			}
			usleep(200);
        }
        @socket_close($connect); // 结束连接
    }
    usleep(200);
}

class Minecraft extends Thread {
	public function __construct($arg){
        $this->arg = $arg;
    }
	
    public function run(){
		$tools = new Tools();
		include("../config/config.php");
        if($this->arg){
            $descriptorspec = array(
				0 => array("pipe", "r"),
				1 => array("pipe", "w"),
				2 => array("pipe", "r")
			);
			if(!file_exists("./Minecraft/" . $corename)) {
				$tools->println("Server Core \"" . $corename . "\" Not Found!");
				@unlink("./status.dat");
				return;
			}
			@file_put_contents("command.dat", "");
			@unlink("./Minecraft/logs/latest.log");
			@unlink("./sora.log");
			$tools->println("Starting Minecraft server ...");
			$process = proc_open("cd Minecraft&java -Xmx" . $jvmmaxmr . "M -Xms" . $jvmmaxmr . "M -jar " . $corename . " -nojline", $descriptorspec, $pipes);
			if (is_resource($process)) {
				$temp = "";
				while(!feof($pipes[1])) {
					$read = @file_get_contents("command.dat");
					if($read !== "") {
						fwrite($pipes[0], $read . "\n");
						file_put_contents("command.dat", "");
						$tools->println("Run command: " . $read);
					}
					$logs = @file_get_contents("./Minecraft/logs/latest.log");
					if(stristr($logs, "Stopping server")) {
						$tools->println("Server Stopped!");
						fclose($pipes[1]);
						fclose($pipes[0]);
						fclose($pipes[2]);
						$return_value = proc_close($process);
						$tools->println("Server return: $return_value");
						@unlink("./status.dat");
						return;
					}
					usleep(200);
				}
			}
        }
    }
}

class logs extends Thread {
	
	public function __construct($arg){
        $this->arg = $arg;
    }
	
	public function run() {
		$temps = "";
		while(true) {
			$logss = @file_get_contents("./Minecraft/logs/latest.log");
			if($temps !== $logss) {
				echo str_replace($temps, "", $logss);
				file_put_contents("sora.log", str_replace($temps, "", $logss), FILE_APPEND);
				$temps = $logss;
			}
			sleep(1);
		}
	}
}

class HttpServer extends Thread {
	public function __construct($port = 21567, $memory = 512, $args){
        $this->hprt = $port;
		$this->mrys = $memory;
		$this->args = $args;
    }
	
    public function run(){
		$tools = new Tools();
        if($this->hprt){
            $descriptorspec = array(
				0 => array("pipe", "r"),
				1 => array("pipe", "w"),
				2 => array("pipe", "r")
			);
			$process = proc_open("java -Xmx" . $this->mrys . "M -Xms" . $this->mrys . "M -jar SoraMC.jar " . $this->hprt . " " . $this->args, $descriptorspec, $pipes);
			while (true) {
				// Not thing to do
                usleep(200);
			}
			$return_value = proc_close($process);
			$tools->println("Httpd return: $return_value");
        }
    }
}

class reStart extends Thread {
	public function __construct($args){
        $this->args = $args;
    }
	
    public function run(){
		$tools = new Tools();
        while(file_exists("status.dat")) {
			// Wait For Stopped.
            usleep(200);
		}
		@file_put_contents("status.dat", "");
		$Minecraft = new Minecraft("start");
		$Minecraft->start();
		return;
    }
}