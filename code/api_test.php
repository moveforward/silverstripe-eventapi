<?php
// test ef api connection

$username = '';
$password = '';

// Request the response in JSON format using the .json extension
$url = 'http://api.eventfinder.co.nz/v2/events.json?rows=2';

$process = curl_init($url);
curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($process);

$collection = json_decode($return);

// Iterate over the events and their image transforms echoing out the event
// name and the image transform URLs
foreach ($collection->events as $event) {
  // echo the name field
  echo $event->name . "\n";
  // iterate over the images collection of images
  foreach ($event->images->images as $image) {
    echo $image->id . "\n";
    // iterate over the transforms collection of transforms
    foreach ($image->transforms->transforms as $transform) {
      echo $transform->url . "\n";
    }
  }
}
