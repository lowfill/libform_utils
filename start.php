<?php


function libform_utils_init(){
    register_page_handler('libform','libform_page_handler');
    register_page_handler('suggest','suggest_page_handler');
}

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
    if(count($page)>0 && in_array($page[0],array('user','group'))){
        $query=get_input("q");
        if(!empty($query)){
            switch($page[0]){
                case 'user':
                    $join = "JOIN {$CONFIG->dbprefix}users_entity ue ON e.guid = ue.guid";
                    $where = "(ue.guid = e.guid
		AND (ue.username LIKE '%$query%'
			OR ue.name LIKE '%$query%'
			)
		)";
                    break;
                case 'group':
                    $join = "JOIN {$CONFIG->dbprefix}groups_entity ue ON e.guid = ue.guid";
                    $where = "(ue.guid = e.guid
		AND (ue.name LIKE '%$query%'
			)
		)";
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
        header("Content-type: application/json");
        echo json_encode($data);
        exit;
    }
}

register_elgg_event_handler('init','system','libform_utils_init');

?>