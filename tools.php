<?php
class Tools {
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
		} else {
			return true;
		}
	}
	
	public function getSoraMC($info) {
		switch($info) {
			case "version":
				return "Ver.10.0.31011";
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
		return json_encode(Array(
			'corename' => $corename,
			'jvmmaxmr' => $jvmmaxmr,
			'javapath' => $javapath
		));
	}
	
	public function saveSystemConfig($cn, $jr, $jp) {
		include("../config/config.php");
		$corename = $cn;
		$jvmmaxmr = $jr;
		$javapath = $jp;
		$config = $this::configTemplate();
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
		@file_put_contents("../config/config.php", $config);
	}
	
	public function defaultSystemConfig() {
		$config = $this::configTemplate();
		$config = str_replace("{password}", "KasuganoSora", $config);
		$config = str_replace("{bindport}", 26817, $config);
		$config = str_replace("{bindhost}", "localhost", $config);
		$config = str_replace("{daemonid}", 1, $config);
		$config = str_replace("{aesenkey}", "EncryptKey0Ex!sF?xwA09xf52", $config);
		$config = str_replace("{contoken}", "AaBbCcDdEeFfGgHhIiJjKkLlMn", $config);
		$config = str_replace("{httpport}", 21567, $config);
		$config = str_replace("{httpmrys}", 512, $config);
		$config = str_replace("{corename}", "spigot-1.12.2-R0.1-SNAPSHOT-b1497.jar", $config);
		$config = str_replace("{jvmmaxmr}", 1024, $config);
		$config = str_replace("{javapath}", "C:\Program Files\Java\jre1.8.0_151\bin\java.exe", $config);
		@file_put_contents("./config.php", $config);
	}
	
	public function file_read($file, $line = 2048) {
		if(!file_exists($file)) {
			return;
		}
		$fp = fopen($file, "r");
		$num = $line;
		$chunk = 4096;
		$fs = sprintf("%u", filesize($file));
		$max = (intval($fs) == PHP_INT_MAX) ? PHP_INT_MAX : filesize($file);
		for ($len = 0; $len < $max; $len += $chunk) {
			$seekSize = ($max - $len > $chunk) ? $chunk : $max - $len;
			fseek($fp, ($len + $seekSize) * -1, SEEK_END);
			$readData = fread($fp, $seekSize) . $readData;
			if (substr_count($readData, "\n") >= $num + 1) {
				preg_match("!(.*?\n){" . ($num) . "}$!", $readData, $match);
				$data = $match[0];
				break;
			}
		}
		fclose($fp);
		return $data;
	}
	
	public function configTemplate() {
		return '<?php
$password = "{password}";
$bindport = {bindport};
$bindhost = "{bindhost}";
$daemonid = {daemonid};
$aesenkey = "{aesenkey}";
$contoken = "{contoken}";
$httpport = {httpport};
$httpmrys = {httpmrys};
$corename = "{corename}";
$jvmmaxmr = {jvmmaxmr};
$javapath = "{javapath}";';
	}
}