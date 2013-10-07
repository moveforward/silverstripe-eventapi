<?php

class EventApi extends Extension {

	private static $api_endpoint = '';
	private static $api_username = '';
	private static $api_password = ''; 
	private static $api_connection_tested = false;

	/*
	constructor
	*/
	function __construct() {
		$config = Config::inst();
	
		self::$api_endpoint = $config->get('EventApi', 'eventFinderApiEndPoint'); // update later to switch endpoints depending upon query type 
		self::$api_username = $config->get('EventApi', 'eventFinderUsername');
		self::$api_password = $config->get('EventApi', 'eventFinderPassword');

	}


	/*
	test the connection before we query for 
	@param null
	@return boolean
	*/
	public function test_connection() {

		$config = Config::inst();

		return self::api_connect() ? true : false;;

	}

	public function api_connect($query_string = null) {

		$qs = '';

		if($query_string && strlen($query_string) > 0) {
			$qs = '?' . $query_string;
		}

		return $result;
	}

	/*
	set up query string for API request
	@param $parameters Array - key: value pairs
	@return String
	*/
	public function set_query_string(Array $parameters) {
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