<?php
class AES {
	public $iv = null;
	public $key = null;
	public $bit = 256;
	private $cipher;
	public function __construct($bit, $key, $iv, $mode) {
		if(empty($bit) || empty($key) || empty($iv) || empty($mode)) {
			return NULL;
		}
		$this->bit = $bit;
		$this->key = $key;
		$this->iv = $iv;
		$this->mode = $mode;
		switch($this->bit) {
			case 192:
				$this->cipher = MCRYPT_RIJNDAEL_192;
				break;
			case 256:
				$this->cipher = MCRYPT_RIJNDAEL_256;
				break;
			default:
				$this->cipher = MCRYPT_RIJNDAEL_128;
		}
		switch($this->mode) {
			case 'ecb':
				$this->mode = MCRYPT_MODE_ECB;
				break;
			case 'cfb':
				$this->mode = MCRYPT_MODE_CFB;
				break;
			case 'ofb':
				$this->mode = MCRYPT_MODE_OFB;
				break;
			case 'nofb':
				$this->mode = MCRYPT_MODE_NOFB;
				break;
			default:
				$this->mode = MCRYPT_MODE_CBC;
		}
	}
	public function encrypt($data) {
		$data = base64_encode(mcrypt_encrypt($this->cipher, $this->key, $data, $this->mode, $this->iv));
		return $data;
	}
	public function decrypt($data) {
		$data = mcrypt_decrypt( $this->cipher, $this->key, base64_decode($data), $this->mode, $this->iv);
		$data = rtrim(rtrim($data));
		return $data;
	}
}