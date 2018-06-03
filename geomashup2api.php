<?php
/**
 * Plugin Name: GeoMashup2WPJSON
 * Description: Adds the geo coordinates of GeoMashup to the Wordpress API
 * Version: 1.0.0
 * Author: Marc Herbrechter
 * Author URI: https://www.island-ringstrasse.de/
 * License: Apache
 *
 * @package GeoMashup2WPJSON
 */

function get_coordinates($object, $field_name, $request) {
	global $wpdb;
	$location = $wpdb->get_row('SELECT wp_geo_mashup_locations.lat, wp_geo_mashup_locations.lng FROM wp_geo_mashup_locations INNER JOIN wp_geo_mashup_location_relationships ON wp_geo_mashup_locations.id = wp_geo_mashup_location_relationships.location_id
WHERE wp_geo_mashup_location_relationships.object_id = ' . $object["id"]);
	if ($location) {
		return $location->lat . "," . $location->lng;
	} else {
		return null;
	}
}

function noop() {
	// TODO We don't update for now, i.e. POST to the API would be useless.
}

add_action('rest_api_init', function() {
	register_rest_field('post', 'geo',
		array(
			'get_callback' => 'get_coordinates',
			'update_callback' => 'noop',
			'schema' => null
		)
	);
});
