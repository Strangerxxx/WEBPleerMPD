<?php
	class Pleer{
		private $username;
		private $password; 
		private $access_token;
		private $ch;
		private $auth = false;
		private $token_endpoint;
		private $method_endpoint;

		public function __construct($username, $password,
			$token_endpoint = 'http://api.pleer.com/api/token.php',
			$method_endpoint = 'http://api.pleer.com/resource.php')
		{
			$this->token_endpoint = $token_endpoint;
			$this->method_endpoint = $method_endpoint
			$this->username = $username;
			$this->password = $password;
			$this->ch = curl_init();
			$this->access_token = getAccessToken()

		}

		public function getAccessToken()
		{
			if(!is_null($this->access_token)) return $this->access_token;
			elseif(is_null($this->username) || is_null($this->password)) return false;
			else {
				$rs = json_decode($this->request())
			}

		}

		private function request($url, $method = 'GET', $postfields = array(), $action = false)
		{
			curl_setopt_array($this->ch, array(
				CURLOPT_USERAGENT       => 'MPD/1.0 (+StrangeMPD))',
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_USERPWD			=> (!is_null($this->username) && !is_null($this->password) && $action)? $this->username.':'.$this->password : false,
				CURLOPT_POST            => ($method == 'POST'),
				CURLOPT_POSTFIELDS      => $postfields,
				CURLOPT_URL             => $url
			));
        	return curl_exec($this->ch);
    	}
	}