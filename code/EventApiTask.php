<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends BuildTask {

	protected $title = "Refresh EventFinder data";
	protected $description = "Refresh event data from Event Finder API";


	// controller action to be run by default
	function run() {

		echo EventApi::test_connection();

	}
	
}