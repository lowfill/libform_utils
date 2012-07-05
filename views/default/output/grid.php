<?php
/**
 * Grid output view or Elgg
 * 
 * This view uses Flexigrid (http://flexigrid.info/) to display data in a AJAX way
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 * 
 * @param $vars['internalname']
 * @param $vars['endpoint'] Data source to be called. The name is used for display a view 'grid/<endpoint>_data. See libform_utils/views/default/grid/* for examples
 * @param $vars['extra_params'] Extra params for filter data
 * @param $vars['column_configuration'] An array with a row by each column to be displayed. See flexigrid documentation for all posible options
 * @param $vars[<any flexigrid option>] You can overwrite any flexygrid option
 */
global $CONFIG;

elgg_extend_view('metatags','jquery/flexigrid');

$placeholder = (array_key_exists('internalname',$vars))?$vars['internalname']:'grid';
$endpoint = (array_key_exists('endpoint',$vars)) ? $vars['endpoint'] : 'users';
$url="{$CONFIG->url}pg/grid/{$endpoint}/";
if(array_key_exists('extra_params',$vars)){
	$url = elgg_http_add_url_query_elements($url,$vars['extra_params']);
}

//TODO i18n
$grid_options = array(
	'dataType'=>'json',
	'method'=>'GET',

	'sortname'=>'',
	'sortorder'=>'desc',
	'usepager'=>true,
	'useRp'=>false,
	'rp'=>12,
	'showTableToggleBtn'=>true,
	'width'=>'auto',
	'height'=>310,
	'striped'=>true, //apply odd even stripes
	'novstripe'=>false,
	'minwidth'=>30, //min width of columns
	'minheight'=>80, //min height of columns
	'resizable'=>true, //resizable table
	'errormsg'=>elgg_echo('Connection Error'),
	'nowrap'=>true, //
	'grid_page'=>1, //current page
	'total'=>1, //total pages
	'rpOptions'=>array(10,15,20,25,40),
	'title'=>false,
	'pagestat'=>elgg_echo('libforms:grid:pagestat'),
	'pagetext'=>elgg_echo('libforms:grid:pagetext'),
	'outof'=>elgg_echo('libforms:grid:outof'),
	'findtext'=>elgg_echo('libforms:grid:findtext'),
	'procmsg'=>elgg_echo('libforms:grid:procmsg'),
	'query'=>'',
	'qtype'=>'',
	'nomsg'=>'No items',
	'minColToggle'=>1, //minimum allowed column to be hidden
	'hideOnSubmit'=>true,
	'autoload'=>true,
	'blockOpacity'=>0.5,
	'onDragCol'=>false,
	'onToggleCol'=>false,
	'onChangeSort'=>false,
	'onSuccess'=>false,
	'onError'=>false,
	'onSubmit'=>false // using a custom populate function
);

foreach($vars as $key=>$value){
    if(array_key_exists($key,$grid_options)){
        $grid_options[$key]=$value;
    }
}

$grid_options['url']=$url;

$column_defaults = array(
	'display'=>'Column name',
	'name'=>'name',
	'width'=> 100,
	'sortable'=>false,
	'align'=>'center'
);

$columns_configuration = array();
if(is_array($vars['column_configuration'])){
	foreach($vars['column_configuration'] as $column){
		$columns_configuration[]=array_merge($column_defaults,$column);
	}
}
else{
	$columns_configuration[]=array_merge($column_defaults,array('display'=>'GUID','name'=>'guid','width'=>80,'sortable'=>true));		
	$columns_configuration[]=array_merge($column_defaults,array('display'=>'Name','name'=>'name','width'=>180));		
	$columns_configuration[]=array_merge($column_defaults,array('display'=>'Last access','name'=>'last_login','width'=>100));		
}

$grid_options['colModel']=$columns_configuration;

$options = json_encode($grid_options);
?>
<div id="<?php echo $placeholder;?>">
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#<?php echo $placeholder;?>").flexigrid(<?php echo $options?>);   
	//$("#<?php echo $placeholder;?>").flexigrid();   
});
</script>

