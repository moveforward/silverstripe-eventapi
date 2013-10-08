<?php

class EventApi extends Extension {

	private $api_endpoint = '';
	private $api_username = '';
	private $api_password = ''; 
	private $api_connection_tested = false;

	/*
	constructor
	*/
	function __construct() {
		$config = Config::inst();
	
		$this->api_endpoint = $config->get('EventApi', 'eventFinderApiEndPoint'); // update later to switch endpoints depending upon query type 
		$this->api_username = $config->get('EventApi', 'eventFinderUsername');
		$this->api_password = $config->get('EventApi', 'eventFinderPassword');

	}


	/*
	test the connection before we query for 
	@param null
	@return boolean
	*/
	public function test_connection() {

		$config = Config::inst();

		return $this->api_connect('rows=1') ? true : false;;

	}

	public function api_connect($query_string = null) {

		$qs = '';

		if($query_string && strlen($query_string) > 0) {
			$qs = '?' . $query_string;
		}

		$process = curl_init($this->api_endpoint . $qs);
		curl_setopt($process, CURLOPT_USERPWD, $this->api_username . ":" . $this->api_password);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		$event_data = curl_exec($process);

		return $event_data;
	}

	/*
	utiliuty function to set up query string from array key / value pairs
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
	get a set of data from EF based on queries and modified date
	@param String $qsParams - query string parameters to pass to query 
	@param $modified_since DateTime
	@return Array - structured event data 
	*/
	public function ef_query(Array $qsParams, String $modified_since) {

		if(!$this->api_connection_tested) {
			if(!$this->test_connection()) {
				// TODO: log error
				return false;
			}
			else {
				$this->api_connection_tested = true;
			}
		}
		
		$qs = '';


		if(!empty($qsParams)) {
			$qs = $this->set_query_string($qsParams);
		}

		$data = $this->api_connect($qs);

		// format as array
		return Convert::json2array($data);
	}

	/*
	take some parameters and retunr a full result set from EF
	- acts as a wrapper and control for multiple requests to EF via get_data()
	- EF result sets are limited to max of 20 per query so we use this function to run repeat requests and bundle
	the results into one array 
	@param Array $qsParams - query string parameters to filter the query
	@param Int $limit - a hard limit on the result set size you want returned
	@param String $modified_since - timestamp to retrieve only events updated / created since specified time
	@return Array - events from query parameters
	*/
	public function get_dataset(Array $qsParams, Int $limit, String $modified_since) {

		$qsParams['rows'] = 20; // current EF max result set limit
		$pointer = 0; // current pointer
		$events = array(); // container for result set

		// we use the first query to determine the size of the full result set
		// redundant but allows the while() loop to be written more clearly
		$result = $this->ef_query($qsParams);

		if(!$result) {
			// TODO: error handling
			return false;
		}

		$total = $result['@attributes']['count']; // full result set size

		while($total > $pointer) {
			$qsParams['offset'] = $pointer;	
			$result = $this->ef_query($qsParams);
			$pointer += count($result['events']);

			// don't try and fetch more rows than there are results left from the offset - EF doesn't seem to like this
			if($total - $pointer < $qsParams['rows']) {
				$qsParams['rows'] = $total - $pointer;
			}

			foreach($result['events'] as $event) {
				array_push($events, $event);
			}
			// break the query intervals up slightly - try and avoid any internal EF rate limiting
			usleep(300);
		}

		return $events;
	}

}