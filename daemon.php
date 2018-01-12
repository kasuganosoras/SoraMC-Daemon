<?php
error_reporting(E_ALL);
/**
 *
 *	SoraMC Server Daemon
 *
 **/
include("AES.php");
include("tools.php");
include("../config/config.php");
$keys = $aesenkey;
$port = $bindport;
$host = $bindhost;
$auth = $contoken;
$mrys = $httpmrys;
$hprt = $httpport;
$tools = new Tools();
$opi = array();
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket, 5);
echo "    _  __                                         ____                  \n";
echo "   | |/ /__ _ ___ _   _  __ _  __ _ _ __   ___   / ___|  ___  _ __ __ _ \n";
echo "   | ' // _` / __| | | |/ _` |/ _` | '_ \ / _ \  \___ \ / _ \| '__/ _` |\n";
echo "   | . \ (_| \__ \ |_| | (_| | (_| | | | | (_) |  ___) | (_) | | | (_| |\n";
echo "   |_|\_\__,_|___/\__,_|\__, |\__,_|_| |_|\___/  |____/ \___/|_|  \__,_|\n";
echo "                        |___/                                           \n";
echo "                                                  Minecraft Server Panel\n";
$tools->println("SoraMC Version 1.0.0.0");
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
while(true) {
    $connect = socket_accept($socket);
    if($connect !== false){
        while($read = @socket_read($connect, 8192)){
			socket_getpeername($connect, $clientip, $clientport);
			$aes = new AES($bit = 256, $key = md5($keys), $iv = md5($keys), $mode = 'cfb');
			if(preg_match("/^[A-Za-z0-9\=\/\+]+$/", $read)) {
				$tools->println("New request from socket: " . $clientip . ":" . $clientport);
				//$contype = "socket";
				$decrypt = $aes->decrypt($read);
				// $tools->println($decrypt);
				$action = json_decode($decrypt, true);
				// echo $action[0]->action;
				if($action["action"] == "login") {
					// echo "Client: " . $action["key"] . "/" . $keys;
					if($action["key"] == $keys) {
						$res = $tools->status(200, $auth);
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret));
					} else {
						$res = $tools->status(403, 'Auth Failed');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret));
					}
				} else {
					if($action["token"] == $keys) {
						switch($action["action"]) {
							case "start":
								if(file_exists("status.dat")) {
									$res = $tools->status(502, 'Server Is Running');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									@file_put_contents("status.dat", "");
									$thread = new Minecraft("start");
									$thread->start();
									$res = $tools->status(200, 'Successful Start Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								}
							case "stop":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "stop");
									$res = $tools->status(200, 'Successful Stop Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								}
							case "restart":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "stop");
									$restart = new reStart("start");
									$restart->start();
									$res = $tools->status(200, 'Successful reStart Server');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								}
							case "status":
								if(file_exists("status.dat")) {
									$res = $tools->status(200, 'Server Online');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								}
							case "daemonversion":
								$res = $tools->status(403, $tools->getSoraMC("version"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "daemonencrypt":
								$res = $tools->status(403, $tools->getSoraMC("encrypt"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "getserverconfig":
								$res = $tools->status(403, $tools->getSoraMC("config"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "getsystemconfig":
								$res = $tools->status(403, $tools->getSystemConfig());
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "saveconfig":
								@file_put_contents("./Minecraft/server.properties", base64_decode($action["args"]));
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "savesystemconfig":
								$sconf = json_decode(base64_decode($action["args"]), true);
								$tools->saveSystemConfig($sconf["corename"], $sconf["jvmmaxmr"], $sconf["javapath"]);
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
								break;
							case "sendcommand":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									$res = $tools->status(200, 'Successful Run Command');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret));
									break;
								}
							case "sendmessage":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "say " . iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									@file_put_contents("./Minecraft/playerchat.log", "[" . date("H:i:s") . "]<Server> " . iconv("UTF-8", "GB2312", base64_decode($action["args"])) . "\n", FILE_APPEND);
									$res = $tools->status(200, 'Successful Send Message');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret));
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret));
									break;
								}
							default:
								$res = $tools->status(404, 'Action Not Found');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret));
						}
					} else {
						$res = $tools->status(403, 'Token Forbidden');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret));
					}
				}
			}
        }
        @socket_close($connect);
		//$tools->println("Connection Closed: " . $contype . "/" . $connect);
		/*
			if(strstr($read, "GET /?pass=" . $auth . " HTTP/")) {
				$logfile = @mb_substr(file_get_contents("./Minecraft/logs/latest.log"), -16384);
				$ret = $tools->httpString($logfile, 200, "text/plain", "GB2312");
				socket_write($connect, $ret, strlen($ret));
				//$tools->println("New request from http: " . $connect);
				socket_close($connect);
				//$contype = "http";
				continue;
			} elseif(strstr($read, "GET /favicon.ico HTTP/")) {
				$ret = $tools->httpString(file_get_contents("favicon.ico"), 200, "image/x-icon");
				socket_write($connect, $ret, strlen($ret));
			} else {
				$ret = $tools->httpString("403 Forbidden", 403);
				socket_write($connect, $ret, strlen($ret));
				$tools->println("The client provides an invalid password, your server's IP address may have been leaked.", false, 1);
			}
		*/
    }
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
				}
				//$tools->println("Server Stopped!");
				
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
		}
		@file_put_contents("status.dat", "");
		$Minecraft = new Minecraft("start");
		$Minecraft->start();
		return;
    }
}