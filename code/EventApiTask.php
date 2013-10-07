<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends Controller {

	private static $allowed_actions = array('index');

	// controller action to be run by default
	function index() {

		echo EventApi::test_connection();

	}
	
}