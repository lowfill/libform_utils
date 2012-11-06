<?php
$plugin = $vars["entity"];


$location_options = array(
		"yes" => elgg_echo('option:yes'),
		"no" => elgg_echo('option:no')
);

$location = $plugin->location;

if(empty($location)){
	$location = 'yes';
}

$settings .= "<div>";
$settings .= elgg_echo('libform:settings:location');
$settings .= "<br />";
$settings .= elgg_view('input/dropdown', array('name' => 'params[location]', 'value' => $location, 'options_values' => $location_options));
$settings .= "</div>";

echo elgg_view_module("info", elgg_echo("libform:settings:location:title"), $settings);

