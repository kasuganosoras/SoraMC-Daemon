<?php
date_default_timezone_set(/** @lang text */
    "Asia/Shanghai");
class Tools {
    public function __construct(){
        include("../config/config.php");
        $this->config = $config;
    }
	public function status($code, $text) {
		$array = Array(
			'status' => $code,
			'description' => $text
		);
		return json_encode($array);
	}
	
	public function httpString($string, $status = 200, $type = "text/html", $encode = "UTF-8", $server = "SoraMC") {
		switch($status) {
			case 200:
				$scode = "200 OK";
				break;
			case 403:
				$scode = "403 Forbidden";
				break;
			case 404:
				$scode = "404 Not Found";
				break;
			case 500:
				$scode = "500 Internal Server Error";
				break;
			case 502:
				$scode = "502 Bad Gateway";
				break;
			default:
				$scode = "200 OK";
		}
		return "HTTP/1.0 " . $scode . "\nServer: " . $server 
			. "\nContent-Type: " . $type . "; charset=" . $encode 
			. "\nAccess-Control-Allow-Origin: *" 
			. "\nContent-Length: " . strlen($string) . "\n\n" . $string
			. "\nConnection: Close";
	}
	
	public function println($string, $retstr = false, $type = 0, $ender = "\n") {
		switch($type) {
			case 0:
				$stype = "INFO";
				break;
			case 1:
				$stype = "WARN";
				break;
			case 2:
				$stype = "ERROR";
				break;
			default:
				$stype = "INFO";
		}
		if($retstr) {
			return "[" . date("H:i:s") . " " . $stype . "] " . $string . $ender;
		} else {
			echo "[" . date("H:i:s") . " " . $stype . "] " . $string . $ender;
		}
	}
	
	public function is_json($string){
		if(json_decode($string) == null) {
			return false;
		}
		return true;
	}
	
	public function getSoraMC($info = "version") {
		switch($info) {
			case "version":
				return "Ver.1.0.0_dhdjReloaded";
				break;
			case "encrypt":
				return "AES_256_CFB";
				break;
			case "config":
				return @file_get_contents("./Minecraft/server.properties");
			default:
				return "";
		}
	}
	
	public function getSystemConfig() {
		include("../config/config.php");
		return json_encode($this->config);
	}

    /**
     * @param $cn
     * @param $jr
     * @param $jp
     */
    public function saveSystemConfig($cn, $sm, $jc) {
        $ConfigContent = $this->GetConfigContent();
        $Replacement=array('"CoreName" => "'.$this->config->CoreName.'"','"JavaCommand" => "'.$this->config->JavaCommand.'"','"ServerMemories" => '.$this->config->ServerMemories.'');
        $Replace=array('"CoreName" => "'.$cn.'"','"JavaCommand" => "'.$jc.'"','"ServerMemories" => '.$sm.'');
        $ConfigContent = str_replace($Replacement,$Replace,$ConfigContent);
        file_put_contents("../config/config.php", $ConfigContent);
        if(file_get_contents("../config/config.php") == $ConfigContent){
            return true;
        }
        return false;
        /* @Deprecated
		$config = $this->configTemplate();
		$config = str_replace("{password}", $password, $config);
		$config = str_replace("{bindport}", $bindport, $config);
		$config = str_replace("{bindhost}", $bindhost, $config);
		$config = str_replace("{daemonid}", $daemonid, $config);
		$config = str_replace("{aesenkey}", $aesenkey, $config);
		$config = str_replace("{contoken}", $contoken, $config);
		$config = str_replace("{httpport}", $httpport, $config);
		$config = str_replace("{httpmrys}", $httpmrys, $config);
		$config = str_replace("{corename}", $corename, $config);
		$config = str_replace("{jvmmaxmr}", $jvmmaxmr, $config);
		$config = str_replace("{javapath}", $javapath, $config);
        */
	}
	public function GetConfigContent() {
        return file_get_contents("../config/config.php");
    }
    /* @Deprecated
    public function configTemplate() {
        return '<?php
static $config = array(
    "TPS" => 5,//Daemon处理频率[Ticks Per Second(帧每秒)][建议使用5的整数倍,否则可能需要round,无法精确]
    "Password" => "password",//链接密码
    "DaemonPort" => 26817,//Daemon通讯端口
    "LogPort" => 21567,//日志服务器端口
    "LogMemories" => 512,//日志服务器内存大小
    "ServerMemories" => 1024,//服务器内存大小
    "CoreName" => "server.jar",//核心名字
    "ServerHost" => "localhost",//服务器绑定IP[建议使用localhost或127.0.0.1]
    "DaemonID" => "1",//DaemonID
    "AESToken" => "EncryptKey0Ex!sF?xwA09xf52",//字面意思
    "ConToken" => "AaBbCcDdEeFfGgHhIiJjKkLlMn",//不用管就好
    "JavaCommand" => "java"//java路径,path设置过请用java
);';
	}
    */
}