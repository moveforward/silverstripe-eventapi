<?php

class EventApi extends Extension {

	private static $api_endpoint = '';
	private static $api_username = '';
	private static $api_password = ''; 
	private static $api_connection_tested = false;

	/*
	constructor
	*/
	public static function __construct() {
		$config = Config::Inst();
	
		self::$api_endpoint = $config->get('eventFinderApiEndpoint'); // update later to switch endpoints depending upon query type 
		self::$api_username = $config->get('eventFinderUsername');
		self::$api_password = $config->get('eventFinderPassword');

	}


	/*
	test the connection before we query for 
	@param null
	@return boolean
	*/
	public static function test_connection() {

		$data = self::api_connect();

	}

	public static function api_connect($query_string = null) {

		var $qs = '';

		if($query_string && strlen($query_string) > 0) {
			$qs = '?' . $query_string;
		}

		$process = curl_init(self::$api_endpoint . $qs);
		curl_setopt($process, CURLOPT_USERPWD, self::$api_username . ":" . self::$api_password);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		return curl_exec($process);
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
			// strip last &
			$qs = substr($qs, 0, strlen($qs) - 1);
		}

		return $qs;
	}

	/*
	get latest data
	@param String $qsParams - query string parameters to pass to query 
	@param $modified_since DateTime
	@return Array - structured event data 
	*/
	public function get_data(Array $qsParams) {

		if(!self::$api_connection_tested) {
			if(!self::testConnection()) {
				// TODO: log error
				return false;
			}
		}
		
		$qs = '';


		if(!empty($qsParams)) {
			$qs = self::setQueryString($qsParams);
		}

		$data = self::api_connect();

		// format as array
		return Convert::jsontoarray($data);
	}







}