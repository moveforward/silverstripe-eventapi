<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends BuildTask {

	protected $title = "Test EventFinder data connection";
	protected $description = "Pull 500+ event results from Event Finder API";


	// controller action to be run by default
	function run($request) {

		$ef = new EventApi;

		$query_attributes = array('category' => 6, 'location' => 363); 

		$results = $ef->get_dataset($query_attributes); 

		echo count($results) . ' results returned'; 

		foreach ($results as $result) {
			echo '<p>' . $result['name'] . ' / ' . $result['location_summary'] . '</p>';
		}

	}
	
}