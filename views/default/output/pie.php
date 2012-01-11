<?php
/**
 * Pie graph output view or Elgg
 * 
 * This view uses jGCharts (http://maxb.net/scripts/jgcharts/include/demo/) to display data
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 * 
 * @param $vars['internalname']
 * @param $vars['labels']
 * @param $vars['values']
 * @param $vars['legends']
 * @param $vars['legend_position'] 
 * @param $vars['size']
 */
// FIXME update to 1.8
elgg_extend_view("metatags","jquery/jqcharts");

$legends = array();
$labels = array("'Uno','Dos'");
$values = array("1,2");
$size = "350x100";

if(isset($vars['labels'])){
	$labels = $vars['labels'];
}
if(isset($vars['values'])){
	$values = $vars['values'];
}
if(isset($vars['legends'])){
	$legends = $vars['legends'];
}
$legends_position = "";
if(isset($vars['legend_position'])){
	$legends_position = $vars['legend_position'];
}

if(isset($vars['size'])){
	$size = $vars["size"];
}

$legends = implode(",",array_map(create_function('$item',"return '\"'.\$item.'\"';"),$legends));
$labels = implode(",",array_map(create_function('$item',"return '\"'.\$item.'\"';"),$labels));
$values = implode(",",$values);
?>
<div id="pie_container_<?php echo $vars['internalname']?>" class="<?php echo $vars['class']?>_container pie_container">
<?php if(isset($vars['title'])){?>
<h3><?php echo $vars['title']?></h3>
<?php }?>
<div id="<?php echo $vars["internalname"]?>" class="<?php echo $vars["class"]?>"></div>

</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    var api = new jGCharts.Api();
    var labels = [<?php echo $labels?>];
    var legends = [<?php echo $legends?>];
    var values = [<?php echo $values?>];
    var params = {type:"p",size:'<?php echo $size;?>',data:values};
    if (legends.length > 0){
        params.legend=legends;
    	params.legend_position='<?php echo $legends_positions?>';
    	params.axis_type='';
    }
    else{
        params.axis_labels=labels;
    }
    jQuery('<img>').attr('src', api.make(params)).appendTo("#<?php echo $vars["internalname"]?>");

});
</script>