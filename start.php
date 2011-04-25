<?php
/**
 * Elgg libform_utils plugin
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */

/**
 * libform_utils initialization
 */
function libform_utils_init(){
	elgg_extend_view('css','libform/css');
	
	register_page_handler('libform','libform_page_handler');
	register_page_handler('suggest','suggest_page_handler');
	register_page_handler('grid','grid_page_handler');

	set_include_path(dirname(__FILE__)."/vendors/pear/:".get_include_path());
}

/**
 * libform_utils page handler
 * @param $page
 */
function libform_page_handler($page){
	include dirname(__FILE__)."/examples.php";
}

/**
 * Suggest page handler
 *
 * Process urls that match /pg/suggest/(user|group)
 * @param $page
 */
function suggest_page_handler($page){
	global $CONFIG;
	$query=get_input("q");
	if(count($page)>0 && in_array($page[0],array('user','group'))){
		if(!empty($query)){
			switch($page[0]){
				case 'user':
					$join = "JOIN {$CONFIG->dbprefix}users_entity ue ON e.guid = ue.guid";
					$where = "(ue.guid = e.guid AND (ue.username LIKE '%$query%' OR ue.name LIKE '%$query%'))";
					break;
				case 'group':
					$join = "JOIN {$CONFIG->dbprefix}groups_entity ue ON e.guid = ue.guid";
					$where = "(ue.guid = e.guid	AND (ue.name LIKE '%$query%'))";
					break;
			}
			$options = array(
            'type'=>$page[0],
            'subtype'=>ELGG_ENTITIES_NO_VALUE,
        	'joins'=>array($join),
        	'wheres'=>array($where),
			);
			$entities = elgg_get_entities($options);
		}
		$data = array();
		if(!empty($entities)){
			foreach($entities as $entity){
				$data[]=array('id'=>$entity->guid,'name'=>$entity->name);
			}
		}
	}
	else{
		$function_name = "{$page[0]}_suggest_hook";
		$extra_param = (isset($page[1]))?$page[1]:"";
		if(function_exists($function_name)){
			$data = $function_name($query,$extra_param);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * Grid page handler
 * @param $page
 */
function grid_page_handler($page){
	$handler = (isset($page[0]))?$page[0]:"users";
	$view = "grid/{$handler}_data";
	if(elgg_view_exists($view)){
		header("Content-type: application/json");
		echo elgg_view($view);
		exit;
			
	}
}
/**
 * Get the list of validators and messages from an string
 *
 * @param string $validator_str ';' separated validators string
 * @param string $messages String with the custom messages for the field
 * @return string
 */
function libform_get_validators($validator_str,$messages=""){
	$validators = array();
	$u_validators = explode(";",$validator_str);
	if(!is_array($u_validators)){
		$u_validators = array($u_validators);
	}
	foreach($u_validators as $validator){
		if(empty($validator)) continue;
		if(strpos($validator,":")>0){
			$validators[]="{$validator}";
		}
		else{
			$validators[]="{$validator}:true";
		}
	}
	if(!empty($messages)){
		$validators[]="messages:{".$messages."}";
	}
	return "{".implode(",",$validators)."}";
}
function libform_get_countries(){
	static $countries = array();
	$raw = file(dirname(__FILE__)."/vendors/countries.txt");
	if(empty($countries)){
		foreach($raw as $country){
			$country = explode(";",$country);
			$countries[$country[0]]=$country[1];
		}
		$countries = array_map(elgg_echo,$countries);
	}
	return $countries;
}
function libform_get_country($code){
	$countries = libform_get_countries();
	return $countries[$code];
}
register_elgg_event_handler('init','system','libform_utils_init');

?>