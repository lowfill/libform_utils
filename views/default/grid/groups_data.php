<?php 
/**
 * Grid groups data view
 * 
 * Handle the request made from the flexigrid view and returns basic group's data
 * 
 * WARNING: If you change your columns configuration don't forget to overwrite this view too!
 * 
 * @param page
 * @param rp
 * @param sortname
 * @param sortorder
 */

global $CONFIG;
$page = get_input('grid_page',1);
$rp = get_input('rp',100);
$sortname = get_input('sortname','e.guid');
$sortorder = get_input('sortorder','desc');
$start = (($page-1) * $rp);

$options = array('types'=>'group','count'=>true,'limit'=>50);
$count = elgg_get_entities($options);
$options['count']=false;
$options['limit']=$rp;
$options['offset']=$start;
if(!empty($sortname)){
	$options['order_by']="$sortname $sortorder";
}
$entities = elgg_get_entities($options);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");

$rows = array();
if(!empty($entities)){
	foreach($entities as $entity){
		$row = array();
		$row['id']=$entity->guid;
		$name = mb_convert_case("$entity->name",MB_CASE_TITLE,'UTF-8');
		$name = "<a href=\"{$entity->getUrl()}\">$name</a>";
		$description = $entity->description;
		$row['cell']=array(
			$entity->guid,
			$name,
			$description
		);
		$rows[]=$row;
	}
}

$data = array(
	'grid_page'=>$page,
	'total'=>$count,
	'rows'=>$rows
);

$data =json_encode($data,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
echo $data;

?>