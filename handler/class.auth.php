<?php
class JWTAuth {
	
	public static function encode($header, $payload) {
		// base64 encoding
		$en_header = self::base64UrlEncode($header);
		$en_payload = self::base64UrlEncode($payload);
		$data  = $en_header. '.' .$en_payload;

		// signing with hmac sha256. when set to true, outputs raw binary data.
		$signature = hash_hmac('SHA256', $data, 'SECRETKEY', true); 
		$en_signature = self::base64UrlEncode($signature);

		return $en_header. '.' .$en_payload. '.' . $en_signature;
	}

	public static function Verify() {
		if(isset($_COOKIE["token"])) {
			$cookie_token =  $_COOKIE["token"];
			$current_hash = self::decode($cookie_token); // from cookie
		
			$split = explode(".", $current_hash);
			$header = $split[0];
			$payload = $split[1];
	
			$re_encode = JWTAuth::encode($header, $payload);
	
			if(!strcmp($split[2], explode(".", $re_encode)[2])) {  
				return json_decode($payload)->name;
			} else {
				echo "401 UNAUTHORIZED ACCESS!";
				die();
			}
		} else {
			echo "401 UNAUTHORIZED ACCESS!";
			die();
		}
	}

	public static function decode($payload) {
		$split = explode(".", $payload);
		$concat = self::base64UrlDecode($split[0]). '.' .self::base64UrlDecode($split[1]). '.' .$split[2];
		return $concat;
	}

	public function base64UrlEncode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	public function base64UrlDecode($data) {
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	} 
}

?>
