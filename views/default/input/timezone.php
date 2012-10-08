<?php
/**
* Elgg timezone input
* Displays a select box showing a list of timezones. This timezones list was borrowed from the Google's timezone calendar plugin
*
* @package ElggLibFormUtils
* @subpackage Core
* @author Curverider Ltd
* @author Diego Andrés Ramírez Aragón
* @link http://github.com/lowfill/libform_utils
*
* @uses $vars['value'] The current value, if any
* @uses $vars['js'] Any Javascript to enter into the input tag
* @uses $vars['internalname'] The name of the input field
* @uses $vars['disabled'] If true then control is read-only
* @uses $vars['class'] Class override
* @uses $vars['validate'] The validator rules
* @uses $vars['validate_messages'] The custom validator messages
* @uses $vars['default_time_zone'] The default timezone if there is nothing selected
*/


$vars['options_values'] = libform_get_timezones();

if(!empty($vars['default_time_zone']) && empty($vars['value'])){
	$vars['value']=$vars['default_time_zone'];
}
else if(empty($vars['value'])){
	$tz = date_timezone_get(new DateTime());
	$vars['value']= timezone_name_get($tz);
}

echo elgg_view('input/pulldown',$vars);

?>