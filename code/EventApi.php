<?php

class EventApi extends Extension {

	private static $api_endpoint = '';

	/*
	set the api endpoint we will be querying
	*/
	public static function set_api_endpoint($url) {
		self::$api_endpoint = $url;
	}

	/*
	get the configured username & key
	@param null
	@return Array - key / value pair for credentials
	*/
	public static function get_credentials() {
		$config = Config::inst();
		$username = $config->get('username');
		$password = $config->get('password');

		return array('username' => $username, 'password' => $password);

	}


	/*
	test the connection to see if it is up / working
	@param null
	@return boolean
	*/
	public static function test_connection() {

	}

	/*
	set up query string for API request
	@param $parameters Array - key: value pairs
	@return String
	*/
	public static function set_query_string(Array $parameters) {
		$qs = '?';

		foreach ($parameters as $key => $value) {
			$qs .= urlencode($key) . '=' . urlencode($value).'&';	
		}

		if(strlen($qs) > 0) {
			$qs = substr($qs, 0, strlen($qs) - 1);
		}

		return $qs;
	}

	/*
	get latest data
	@param $modified_since DateTime
	@return Array - structured event data 
	*/
	public function get_data(Array $qsParams) {
		
		var $url = self::$api_endpoint;

		if(!self::testConnection()) {
			// TODO: log error
			return false;
		}

		if(!empty($qsParams)) {
			$qs = self::setQueryString($qsParams);
			$url .= '?' . $qs;
		}
		// add user credentials
		$credentials = self::get_credentials();

		// get data
		$feed = new RestfulService($url);
	   	$conn = $feed->request()->getBody();
	    $attr = $feed->getAttributes($conn, "event");
	    //print_r($attr);

		// format as array
		return Convert::XMLtoArray($attr);

		// return
	}







}