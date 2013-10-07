<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends BuildTask {


	// controller action to be run by default
	function run() {

		echo EventApi::test_connection();

	}
	
}