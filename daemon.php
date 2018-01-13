<?php
// DEBUG �ã���ʽ����ʱɾ��
error_reporting(E_ALL);
/**
 *
 *	SoraMC Server Daemon
 *
 *	������ʹ�� GNU GPL v3 Э�鿪Դ
 *
 *	ʹ��ʱ������Э�� ( LICENSE )
 *
 **/
 
// ���������͹����࣬���������ļ�
include("AES.php");
include("tools.php");
if(!file_exists("config.php")) {
	(new Tools())->defaultSystemConfig();
}
include("config.php");
// ���������ļ�
$keys = $aesenkey;
$port = $bindport;
$host = $bindhost;
$auth = $contoken;
$mrys = $httpmrys;
$hprt = $httpport;
// Tools ������
$tools = new Tools();
echo "    _  __                                         ____                  \n";
echo "   | |/ /__ _ ___ _   _  __ _  __ _ _ __   ___   / ___|  ___  _ __ __ _ \n";
echo "   | ' // _` / __| | | |/ _` |/ _` | '_ \ / _ \  \___ \ / _ \| '__/ _` |\n";
echo "   | . \ (_| \__ \ |_| | (_| | (_| | | | | (_) |  ___) | (_) | | | (_| |\n";
echo "   |_|\_\__,_|___/\__,_|\__, |\__,_|_| |_|\___/  |____/ \___/|_|  \__,_|\n";
echo "                        |___/                                           \n";
echo "                                                  Minecraft Server Panel\n";
// ���� Socket
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(@socket_bind($socket, $host, $port) === false) {
	$tools->println("**** FAILED TO BIND TO PORT!", false, 2);
	$tools->println("Perhaps a server is already running on that port?", false, 2);
	exit;
}
@socket_listen($socket, 5);
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
// ���� Minecraft ������ļ���
if(!file_exists("./Minecraft/")) {
	$tools->println("Minecraft server folder not found, try to create it ...");
	if(@mkdir("./Minecraft/", 0777)) {
		$tools->println("Successful create Minecraft server folder");
	} else {
		$tools->println("Failed to create Minecraft server folder, Perhaps you don't have enough Permission(s)");
	}
}
while(true) {
	// �������Կͻ��˵�����
    $connect = socket_accept($socket);
    if($connect !== false){
		// ����ͻ��˷��͵����ݣ����������ó� 8192 һ�����㹻����
        while($read = @socket_read($connect, 8192)){
			// �õ��ͻ�����Ϣ
			socket_getpeername($connect, $clientip, $clientport);
			// ���� AES ����
			$aes = new AES($bit = 256, $key = md5($keys), $iv = md5($keys), $mode = 'cfb');
			/**
			 *
			 *	�жϿͻ��˷��͹��������ݣ��������Э�����淶�� Base64�����������
			 *
			 **/
			if(preg_match("/^[A-Za-z0-9\=\/\+]+$/", $read)) {
				$tools->println("New request from socket: " . $clientip . ":" . $clientport); // DEBUG
				$decrypt = $aes->decrypt($read); // ��������
				$action = json_decode($decrypt, true);
				if($action["action"] == "login") {
					if($action["key"] == $keys) {
						$res = $tools->status(200, $auth);
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // ��֤�ɹ������� token
					} else {
						$res = $tools->status(403, 'Auth Failed');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // ��֤ʧ�ܣ����� Auth Failed
					}
				} else {
					if($action["token"] == $keys) {
						switch($action["action"]) {
							case "start":
								if(file_exists("status.dat")) {
									$res = $tools->status(502, 'Server Is Running');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �������Ѿ�������
									break;
								} else {
									@file_put_contents("status.dat", "");
									$thread = new Minecraft("start"); //���� Minecraft �����
									$thread->start();
									$res = $tools->status(200, 'Successful Start Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �ɹ�����������
									break;
								}
							case "stop":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "stop");
									$res = $tools->status(200, 'Successful Stop Server');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �ɹ��رշ����
									break;
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �������Ѿ�ֹͣ��
									break;
								}
							case "restart":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "stop");
									$restart = new reStart("start");
									$restart->start();
									$res = $tools->status(200, 'Successful reStart Server');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // �ɹ����������
									break;
								} else {
									$res = $tools->status(502, 'Server Is Stopped');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �����δ����
									break;
								}
							case "status":
								if(file_exists("status.dat")) {
									$res = $tools->status(200, 'Server Online');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // ����������
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // ����������
									break;
								}
							case "daemonversion":
								$res = $tools->status(403, $tools->getSoraMC("version"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // Daemon �汾
								break;
							case "daemonencrypt":
								$res = $tools->status(403, $tools->getSoraMC("encrypt"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // Daemon ���ܷ�ʽ
								break;
							case "getserverconfig":
								$res = $tools->status(403, $tools->getSoraMC("config"));
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // ��ȡ���������
								break;
							case "getsystemconfig":
								$res = $tools->status(403, $tools->getSystemConfig());
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // ��ȡ Daemon ����
								break;
							case "saveconfig":
								@file_put_contents("./Minecraft/server.properties", base64_decode($action["args"]));
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // ������������
								break;
							case "savesystemconfig":
								$sconf = json_decode(base64_decode($action["args"]), true);
								$tools->saveSystemConfig($sconf["corename"], $sconf["jvmmaxmr"], $sconf["javapath"]);
								$res = $tools->status(403, 'Successful save config');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // ���� Daemon ����
								break;
							case "sendcommand":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									$res = $tools->status(200, 'Successful Run Command');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // �ɹ���������
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									@socket_write($connect, $ret, strlen($ret)); // ����˲�����
									break;
								}
							case "sendmessage":
								if(file_exists("status.dat")) {
									@file_put_contents("command.dat", "say " . iconv("UTF-8", "GB2312", base64_decode($action["args"])));
									@file_put_contents("./Minecraft/playerchat.log", "[" . date("H:i:s") . "]<Server> " . iconv("UTF-8", "GB2312", base64_decode($action["args"])) . "\n", FILE_APPEND);
									$res = $tools->status(200, 'Successful Send Message');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // �ɹ�������Ϣ
									break;
								} else {
									$res = $tools->status(502, 'Server Offline');
									$ret = $aes->encrypt($res);
									socket_write($connect, $ret, strlen($ret)); // ����˲�����
									break;
								}
							default:
								$res = $tools->status(404, 'Action Not Found');
								$ret = $aes->encrypt($res);
								socket_write($connect, $ret, strlen($ret)); // ����������
						}
					} else {
						$res = $tools->status(403, 'Token Forbidden');
						$ret = $aes->encrypt($res);
						socket_write($connect, $ret, strlen($ret)); // Token ����ȷ
					}
				}
			}
        }
        @socket_close($connect); // ��������
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