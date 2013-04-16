<?php
/**
 * Libform util examples page
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */


global $CONFIG;

$user_option = get_input('option','comboselect');
$options = array("comboselect","autosuggest","validate","location","grid");
$tabs = array();
foreach($options as $option){
	$selected = ($user_option == $option) ? true : false; 
	$tabs[] = array('title'=>elgg_echo("libform:{$option}"),
					'url'=>$vars['url']."libform/?option=$option",
					'id'=>"libform_{$option}",
				 	'selected'=>$selected);
	
}
echo elgg_view("navigation/tabs",array('tabs'=>$tabs));
echo elgg_view("libform/examples/{$user_option}",$vars);
