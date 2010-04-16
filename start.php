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
        if(function_exists($function_name)){
            $data = $function_name($query);
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit;
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
        error_log($validator." ".strpos($validator,":"));
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
register_elgg_event_handler('init','system','libform_utils_init');

?>