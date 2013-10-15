<?php 
// EventApiTask is used to run periodic refresh from EF API 
class EventApiTask extends BuildTask {

	protected $title = "Test EventFinder data connection";
	protected $description = "Pull a large set of event results from Event Finder API";


	// controller action to be run by default
	function run($request) {

		$ef = new EventApi;

		$query_attributes = array(
			'category' => 6, 
			'location' => 363,
			'modified_since' => date('Y-m-d h:i:s', strtotime('-30 days')),
			'created_since' => date('Y-m-d h:i:s', strtotime('-30 days'))
			); 

		$results = $ef->get_dataset($query_attributes); 

		echo count($results) . ' results returned'; 

		foreach ($results as $result) {
			echo '<p>' . $result['name'] . ' / ' . $result['location_summary'] . '</p>';
		}

	}
	
}