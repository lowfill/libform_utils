<?php
/**
 * Location output view or Elgg
 * 
 * This view uses Flexigrid (http://flexigrid.info/) to display data in a AJAX way
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 */
list($country_value,$state_value,$city_value,$postal_code_value) = explode("||",$vars['value']);
if(!empty($country_value)){
	$country_value = elgg_echo("libforms:{$country_value}");	
}
echo "$country_value, $state_value";
if(!empty($city_value)){
	echo ", $city_value";
}

if(!empty($postal_code_value)){
	echo "  $postal_code_value";
}
?>