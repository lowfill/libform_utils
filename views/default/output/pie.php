<?php
elgg_extend_view("metatags","jquery/jqcharts");

$labels = array("'Uno','Dos'");
$values = array("1,2");
$size = "350x100";

if(isset($vars['labels'])){
	$labels = $vars['labels'];
}
if(isset($vars['values'])){
	$values = $vars['values'];
}

if(isset($vars['size'])){
	$size = $vars["size"];
}
$labels = implode(",",array_map(create_function('$item',"return \"'\".\$item.\"'\";"),$labels));
$values = implode(",",$values);
?>
<div id="pie_container_<?php echo $vars['internalname']?>" class="<?php echo $vars['class']?>_container">
<?php if(isset($vars['title'])){?>
<h3><?php echo $vars['title']?></h3>
<?php }?>
<div id="<?php echo $vars["internalname"]?>" class="<?php echo $vars["class"]?>"></div>

</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    var api = new jGCharts.Api();
    var labels = [<?php echo $labels?>];
    var values = [<?php echo $values?>];
    jQuery('<img>').attr('src', api.make({type:"p",
        								  size:"<?php echo $size?>",
                						  axis_labels:labels,
                						  data:values})).appendTo("#<?php echo $vars["internalname"]?>");

});
</script>