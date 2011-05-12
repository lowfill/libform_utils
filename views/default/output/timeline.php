<?php
/**
 * Timeline output view
 * 
 * This view uses Flot (http://code.google.com/p/flot/) to display data in a AJAX way
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 * 
 * @param $vars['width'] 
 * @param $vars['height'] 
 * @param $vars['series_title'] Title for the series controls section
 * @param $vars['series'] Asociative array with the 'serie'=>'label' configuration   
 * @param $vars['start_date'] Initial timiline date
 * @param $vars['endpoint'] Data source to be called. The name is used for display a view 'timiline/<endpoint>_data. See libform_utils/views/default/timiline/* for examples
 * @param $vars['internalname'] An array with a row by each column to be displayed. See flexigrid documentation for all posible options
 * @param $vars['default_series'] An array with the series to be loaded by default
 */

elgg_extend_view("metatags","jquery/flot");
echo elgg_view("jquery/flot-loader",array('flot_modules'=>array('selection','crosshair','timehelpers')));

$width=600;

if(!empty($vars['width'])){
	$width=$vars['width'];
}

$height=400;
if(!empty($vars['height'])){
	$height=$vars['height'];
}
$series_title = elgg_echo("libforms:timeline:series:title");
if(!empty($vars['series_title'])){
	$series_title=$vars['series_title'];
}
$series = array();
if(!empty($vars['series'])){
	$series = $vars['series'];
}
//TODO Add support for zoom controls
$zoom_controls = array();

?>

<table class="time_line_container">
	<tr>
		<td>
		<div id="time_line_<?php echo $vars['internalname']?>" style="width:<?php echo $width;?>px;height:<?php echo $height;?>px"></div>
		</td>
		<td valign="top">
		<form>
		<table width="100%">
			<tr>
				<td valign="top">
				<h3><?php echo $series_title?></h3>
				<?php foreach($series as $serie=>$serie_label){?> <input
					id="<?php echo $serie?>" class="series"
					type="checkbox" name="<?php echo $serie?>">&nbsp;<label
					for="<?php echo $serie?>"><?php echo $serie_label;?></label><br> 
				<?php }?>
				<br>
				<br>
				<?php
				if(!empty($zoom_controls)){
					foreach($zoom_controls as $control){
						?> <input class="zoom" type="radio" name="zoom"
					value="<?php echo $control?>">&nbsp;<label for="zoom"><?php elgg_echo("libforms:$control")?></label>
					<?php
					}
				}
				else{
				
				?>
					<input class="zoom" type="radio" name="zoom" value="weekly" checked="checked" style="display:none">
				<?php 
				}
				?>
				</td>
			</tr>

		</table>
		</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<div id="time_line_overview_<?php echo $vars["internalname"]?>"
			style="margin-left: 50px; margin-top: 20px; width: <?php echo $width+70?>px; height: 50px"></div>
		</td>
	</tr>
</table>
<?php 
echo elgg_view("output/timelinejs",$vars);

?>