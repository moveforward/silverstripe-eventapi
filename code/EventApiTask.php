<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends BuildTask {

	protected $title = "Refresh EventFinder data";
	protected $description = "Refresh event data from Event Finder API";


	// controller action to be run by default
	function run($request) {

		$ef = new EventApi;
		$result = $ef->get_data(array('category' => 6, 'location' => 363)); // music in wellington

		foreach($result['events'] as $event) {
			echo '<p>' . $event['name'] . '</p>';
		}

		// print_r($result); 

	}
	
}