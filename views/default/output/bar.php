<?php
/**
 * Bar graph output view or Elgg
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
 * @param $vars['barwidth']
 */
// FIXME update to 1.8
elgg_extend_view("metatags","jquery/jqcharts");

$labels = array("'Uno','Dos'");
$values = array("1,2");
$size = "400x400";
$barwidth = "r";

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
if(isset($vars['barwidth'])){
	$barwidth = $vars["barwidth"];
}

$labels = implode(",",array_map(create_function('$item',"return \"'\".\$item.\"'\";"),$labels));
$values = implode(",",$values);
?>
<div id="bar_container_<?php echo $vars['internalname']?>" class="<?php echo $vars['class']?>_container bar_container">
<?php if(isset($vars['title'])){?>
<h3><?php echo $vars['title']?></h3>
<?php }?>
<div id="<?php echo $vars["internalname"]?>" class="<?php echo $vars["class"]?>">&nbsp;</div>

</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    var api = new jGCharts.Api();
    var labels = [<?php echo $labels?>];
    var values = [<?php echo $values?>];
    jQuery('<img>').attr('src', api.make({size:"<?php echo $size?>",
                						  axis_labels:labels,
                						  bar_width:'<?php echo $barwidth?>',
                						  data:values})).appendTo("#<?php echo $vars["internalname"]?>");

});
</script>