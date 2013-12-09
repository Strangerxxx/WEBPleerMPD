<?php
	/**
	 * The PHP class for pleer.com API
	 * @author Stranger <stranger.danek@gmail.com>
	 * @license https://github.com/Strangerxxx/WEBPleerMPD/blob/master/LICENSE
	 * @version 0.1.1
	 */

	class Pleer
	{
		/**
		 * Pleer API application identificator
		 * @var string
		 */
		private $username;

		/**
		 * Pleer API application key
		 * @var string
		 */
		private $password;

		/**
		 * Pleer API access token
		 * @var string
		 */
		private $access_token;

		/**
		 * Instance curl
		 * @var resource
		 */
		private $ch;

		/**
		 * Pleer API token endpoint
		 * @var string
		 */
		private $token_endpoint;

		/**
		 * Pleer API method endpoint
		 * @var string
		 */
		private $method_endpoint;

		/**
		 * Authorization status
		 * @var string
		 */
		public $auth = false;

		/**
		 * Error
		 * @var string
		 */
		public $error;

		/**
		 * Error description
		 * @var string
		 */
		public $error_description;

		/**
		 * Warning
		 * @var string
		 */
		public $warning;

		/**
		 * Warning description
		 * @var string
		 */
		public $warning_description;

		/**
		 * Default Pleer API token endpoint
		 * @const string
		 */
		const TOKEN_ENDPOINT 	=	'http://api.pleer.com/api/token.php';

		/**
		 * Default Pleer API method endpoint
		 * @const string
		 */
		const METHOD_ENDPOINT	=	'http://api.pleer.com/index.php';

		/**
		 * @param	string	$username
		 * @param	string	$password
		 * @param	string	$token_endpoint
		 * @param	string	$method_endpoint
		 * @throws	PleerException
		 * @return	void
		 */
		public function __construct($username, $password, $token_endpoint = TOKEN_ENDPOINT, $method_endpoint = METHOD_ENDPOINT)
		{
			$this->token_endpoint = $token_endpoint;
			$this->method_endpoint = $method_endpoint
			$this->username = $username;
			$this->password = $password;
			$this->ch = curl_init();
			$this->access_token = getAccessToken();
		}

		/**
		 * @return void
		 */
		public function __destruct()
		{
			curl_close($this->ch);
		}

		/**
		 * @throws	PleerException
		 * @return	mixed
		 */
		public function getAccessToken()
		{
			if(!is_null($this->access_token)) return $this->access_token;
			elseif(is_null($this->username) || is_null($this->password)) return false;
			else{
				$rs = json_decode($this->request($this->token_endpoint, 'POST', array('grant_type'=>'client_credentials'), true), true);
				if(isset($rs['error'])){
					$this->error = $rs['error'];
					$this->error_description = $rs['error_description'];
					return false; 
				} else{
					$this->auth = true;
					$this->access_token = $rs['access_token'];
					return $this->access_token;
				}

		}

		/**
		 * @param	string	$query
		 * @param	string	$page
		 * @throws	PleerException
		 * @return	mixed
		 */
		public function tracks_search($query = '', $page = '1')
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
				} else{
					$return_tracks = array();
					foreach ($tracks as $track) {
						array_push($return_tracks, $track)''
					}
					return $return_tracks;
				}
				
			}
		}

		/**
		 * @param	string	$track_id
		 * @throws	PleerException
		 * @return	mixed
		 */
		public function tracks_get_info($track_id)
		{
			if(!$this->auth){
				$this->getAccessToken();
			}
			$rs = json_decode($this->request(
				$this->method_endpoint,
				'POST',
				array(
					'access_token'	=>	$this->access_token,
					'method'		=>	'tracks_get_info',
					'track_id'		=>	$track_id
					)
				),
				true
			);
			if(isset($rs['error'])){
				$this->error = $rs['error'];
				$this->error_description = $rs['error_description'];
				return false;
			} elseif(!$rs['success']){
				$this->warning = 'something_wrong';
				$this->warning_description = $rs['message'];
				return false;
			} else{
				$track = $rs[data];
				return $track;
			}
		}

		/**
		 * @param	string	$track_id
		 * @throws	PleerException
		 * @return	mixed
		 */
		public function tracks_get_lyrics($track_id)
		{
			if(!$this->auth){
				$this->getAccessToken();
			}
			$rs = json_decode($this->request(
				$this->method_endpoint,
				'POST',
				array(
					'access_token'	=>	$this->access_token,
					'method'		=>	'tracks_get_lyrics',
					'track_id'		=>	$track_id
					)
				),
				true
			);
			if(isset($rs['error'])){
				$this->error = $rs['error'];
				$this->error_description = $rs['error_description'];
				return false;
			} elseif(!$rs['success']){
				$this->warning = 'something_wrong';
				$this->warning_description = $rs['message'];
				return false;
			} else{
				$lyrics = $rs[text];
				return $lyrics;
			}
		}

		/**
		 * @param	string	$track_id
		 * @param	string	$reason
		 * @throws	PleerException
		 * @return	mixed
		 */
		public function tracks_get_download_link($track_id, $reason = 'save')
		{
			if(!$this->auth){
				$this->getAccessToken();
			}
			$rs = json_decode($this->request(
				$this->method_endpoint,
				'POST',
				array(
					'access_token'	=>	$this->access_token,
					'method'		=>	'tracks_get_download_link',
					'track_id'		=>	$track_id,
					'reason'		=>	$reason
					)
				),
				true
			);
			if(isset($rs['error'])){
				$this->error = $rs['error'];
				$this->error_description = $rs['error_description'];
				return false;
			} elseif(!$rs['success']){
				$this->warning = 'something_wrong';
				$this->warning_description = $rs['message'];
				return false;
			} else{
				$url = stripslashes($rs[url]);
				return $url;
			}
		}

		/**
		 * @param	string	$url
		 * @param	string	$method
		 * @param	array	$postfields
		 * @param	bool	$auth
		 * @throws	PleerException
		 * @return	string
		 */
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
