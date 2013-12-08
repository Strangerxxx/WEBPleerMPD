<?php
	class Pleer{
		private $username;
		private $password; 
		private $access_token;
		private $ch;
		private $auth = false;
		private $token_endpoint;
		private $method_endpoint;
		public $error;
		public $error_description;
		public $warning;
		public $warning_description;


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
				$rs = json_decode($this->request($this->token_endpoint, 'POST', array('grant_type'=>'client_credentials'), true), true);
				if(isset($rs['error'])) {
					$this->error = $rs['error'];
					$this->error_description = $rs['error_description'];
					return false; 
				} else {
					$this->auth = true;
					$this->access_token = $rs['access_token'];
					return $this->access_token;
				}

		}

		public function tracks_search($query = '', $page = 1)
		{
			if(!$this->auth){
				$this->getAccessToken();
			}
			$rs = json_decode($this->request(
				$this->method_endpoint,
				'POST',
				array(
					'access_token'	=>	$this->access_token,
					'method'		=>	'tracks_search',
					'query'			=>	$query,
					'page'			=>	$page
					)
				),
				true
			);
			if(isset($rs['error'])){
				$this->error = $rs['error'];
				$this->error_description = $rs['error_description'];
				return false;
			} elseif(isset($rs['success']) && $rs['success'] == true){
				$tracks = $rs['tracks'];
				if(empty($tracks)){
					$this->warning = 'no_tracks_found';
					$this->warning_description = 'There is no tracks found by your query';
					return false;
				} else {
					$return_tracks = array();
					foreach ($tracks as $track) {
						array_push($return_tracks, $track)''
					}
					return $return_tracks;
				}
				
			}
		}

		private function request($url, $method = 'GET', $postfields = array(), $auth = false)
		{
			curl_setopt_array($this->ch, array(
				CURLOPT_USERAGENT       => 'MPD/1.0 (+StrangeMPD))',
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_USERPWD			=> (!is_null($this->username) && !is_null($this->password) && $auth)? $this->username.':'.$this->password : false,
				CURLOPT_POST            => ($method == 'POST'),
				CURLOPT_POSTFIELDS      => $postfields,
				CURLOPT_URL             => $url
			));
        	return curl_exec($this->ch);
    	}
	}
	