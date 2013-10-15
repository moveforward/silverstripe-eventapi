silverstripe-eventapi
=====================

A Silverstripe3 module which provides an interface between Silverstripe and the EventFinder API

# Requirements

* an EventFinder API account. (http://www.eventfinder.co.nz/api/v2/index )
* cURL installed and configured on your server (http://curl.haxx.se)

# Installation

Install into the root directory of your site. No other installation steps are necessary.

# Configuration

Update _config/config.yml and add your EventFinder API username and password details in the appropriate places.

# Usage

The module has a task set up in code/EventApiTask which shows how the EventAPI module can be used. 
You can run this task by visiting /dev/tasks on your site and selecting the 'Test Eventfinder data connection' task or by visiting /dev/tasks/EventApiTask directly.
The idea of the module is that it can be integrated with the datamodel of your site and that tasks can be run periodically to refresh any Eventfinder data held by your site.

Calling get_dataset() with any related query parameters will run multiple queries to Eventfinder to return a fuill result set based on your parameters.

This module is is not intended for the purpose of displaying Eventfinder data directly on your site. Latency and result set limits from Eventfinder make this type of display option impractical. 

# Endpoints

Endpoints for Event, Location and Category are currently supported. The default endpoint is Events. To query the Location or Category endpoints, specify 'locations'
or 'categories' as the mode attribute of EventAPI->get_dataset()

# Query Parameters

As an interface to the EventFinder API, the module supports any query parameters which can be passed to the EventFinder API.
The EventApiTask provides a sample of how category, location, modified_since and created_since can be queried for

