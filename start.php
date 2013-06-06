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
	$root = dirname(__FILE__);
	$url_root = elgg_get_site_url()."mod/libform_utils";

	//Register css and javascripts
	elgg_register_css("libform:css","$url_root/css/libform.css");
	// Comboselect
	elgg_register_css("libform:comboselect","$url_root/vendors/jquery-comboselect/jquery-comboselect.css");
	elgg_register_js("libform:comboselect", "$url_root/vendors/jquery-comboselect/jquery-comboselect.js");
	// Autosuggest
	elgg_register_css("libform:autosuggest","$url_root/vendors/jquery-tokeninput/token-input.css");
	elgg_register_css("libform:autosuggest:facebook","$url_root/vendors/jquery-tokeninput/token-input-facebook.css");
	elgg_register_js("libform:autosuggest","$url_root/vendors/jquery-tokeninput/jquery.tokeninput.js");
	// Location
	elgg_register_js("libform:location:i18n","$url_root/vendors/location/location.php");
	elgg_register_js("libform:location","$url_root/vendors/location/location.js");
	// Flexigrid
	elgg_register_css("libform:grid","$url_root/vendors/flexigrid/css/flexigrid/flexigrid.css");
	elgg_register_js("libform:grid","$url_root/vendors/flexigrid/flexigrid.js");
	// Validator
	elgg_register_js("libform:validator","$url_root/vendors/jquery-validate/jquery.validate.js");
	elgg_register_js("libform:validator:aditional","$url_root/vendors/jquery-validate/additional-methods.js");
	elgg_register_js("libform:validator:metadata","$url_root/vendors/jquery-validate/lib/jquery.metadata.js");
	$current_language = get_language();
	if($current_language != "en" && file_exists("$root/vendors/jquery-validate/localization/messages_{$current_language}.js")){
		elgg_register_js("libform:validator:i18n","$url_root/vendors/jquery-validate/localization/messages_{$current_language}.js");
	}

	//Register handlers
	elgg_register_page_handler('libform','libform_page_handler');
	elgg_register_page_handler('suggest','suggest_page_handler');
	elgg_register_page_handler('grid','grid_page_handler');
	elgg_register_page_handler('timeline','timeline_page_handler');

	set_include_path(dirname(__FILE__)."/vendors/pear/:".get_include_path());
}

/**
 * libform_utils page handler
 * @todo Implements this
 * @param $page
 */
function libform_page_handler($page){
	$pages = dirname(__FILE__)."/pages/libform";
	include "$pages/examples.php";
	elgg_pop_context();
	return true;
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
				$data[]=array('id'=>$entity->guid,'name'=>$entity->name,'url'=>$entity->getURL());
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
 * Timeline page handler
 * @param $page
 */
function timeline_page_handler($page){
	$handler = (isset($page[0]))?$page[0]:"users";
	$view = "timeline/{$handler}_data";
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
			$validators["data-rule-".substr($validator,0,strpos($validator,":"))]=substr($validator,strpos($validator,":")+1);
		}
		else{
			$validators["data-rule-$validator"]="true";
		}
	}
	if(!empty($messages)){
		$u_messages = explode(",",$messages);
		if(!empty($u_messages)){
			foreach($u_messages as $message){
				list($validator,$message) = explode(':',$message);
				$validators['data-msg-'.$validator]=$message;
			}
		}
		
	}
	return $validators;
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

function libform_format_attributes(array $attrs,$type='text'){
	if(array_key_exists('internalname', $attrs) && !array_key_exists('internalid', $attrs)){
		$attrs['internalid']=$attrs['internalname'];
	}
	if(array_key_exists('multiple', $attrs) && $attrs['multiple']===true){
		$attrs['internalname'].="[]";
	}
	if(array_key_exists('validate', $attrs)){
		switch($type){
			case "comboselect":
				//FIXME Implements automatic validator for this kind of field
				break;
			default:
				$validators = libform_get_validators($attrs['validate'],$attrs['validate_messages']);
		}
		$attrs += $validators;
		unset($attrs['validate']);
		unset($attrs['validate_messages']);
	}
	// Cleaning old parameters
	unset($attrs['separator']);
	
	return $attrs;
}

elgg_register_event_handler('init','system','libform_utils_init');

?>