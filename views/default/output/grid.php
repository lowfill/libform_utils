<?php
global $CONFIG;

elgg_extend_view('metatags','jquery/flexigrid');
$url = $CONFIG->url."pg/grid/";

$end_point = (array_key_exists('end_point',$vars)) ? $vars['end_point']: 'grid_example/';

$url.=$end_point;

$default_column_config = array('display'=>'Column name','name'=>'name','width'=>10*count('name'),'sortable'=>false,'align'=>'center');

$columns_config = $vars["column_config"];
$columns_configuration = array();
if(is_array($columns_config)){
	foreach($columns_config as $column){
		$columns_configuration[]=array_merge($default_column_config,$column);
	}
}

if(empty($columns_configuration)){
	$columns_configuration[]=$default_column_config;
}
$default_options  = array(
	'sortname'=>null,
	'sortorder'=>null,
	'usepager'=>true,
	'useRp'=>false,
	'rp'=>50,
	'showTableToggleBtn'=>true,
	'width'=>600,
	'height'=>300
);

foreach($vars as $key=>$value){
    if(array_key_exists($key,$default_options)){
        $default_options[$key]=$value;
    }
}

$default_options['url']=$url;
$default_options["dataType"]='json';
$default_options["method"]='GET';
$default_options["colModel"]=$columns_configuration;
$options = json_encode($default_options);
?>
<div
	id="<?php echo $vars['internalname']?>"></div>
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#<?php echo $vars['internalname']?>").flexigrid(<?php echo $options;?>);   
});
</script>


