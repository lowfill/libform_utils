<?php
/**
 * Timezone output view or Elgg
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['timestamp'] Timestamp to be showed
 * @uses $vars['timezone'] Timezone to be used
 * @uses $vars['show_timezone'] If shows timezone info
 * @uses $vars['show_local'] If shows local hour attached or not
 * @uses $vars['format'] Datetime format as in PHP date function. default: m/d/Y - h:ia
 */

elgg_extend_view("metatags","phpjs/date");
elgg_extend_view("metatags","output/timezonejs");

$format = "m/d/Y - h:ia";
$time = $vars['timestamp'];
$timezone = $vars['timezone'];
$show_local = $vars['show_local'];
$show_timezone = $vars['show_timezone'];

if(array_key_exists('format', $vars)){
	$format = $vars['format'];
}
$tz = date_timezone_get(new DateTime());
$default_timezone = timezone_name_get($tz);

if(empty($timezone)){
	$timezone = $default_timezone;
}

$local = new DateTime();
$nolocal = new DateTime();
if(in_array('setTimestamp',get_class_methods('DateTime'))){
	$local->setTimestamp($time);
	$nolocal->setTimestamp($time);
}
else{
	list($hour,$minute,$second)=explode(":",date("H:i:s",$time));
	list($day,$month,$year)=explode(":",date("d:m:Y",$time));
	$local->setDate($year,$month,$day);
	$nolocal->setDate($year,$month,$day);
	$local->setTime($hour,$minute,$second);
	$nolocal->setTime($hour,$minute,$second);
}
$nolocal->setTimezone(new DateTimeZone($timezone));

$noffset = abs($nolocal->getOffset()/60);
$output_format = "%s <small>%s</small>";

if($show_timezone === false){
	$output_format = "%s";
}
printf($output_format,$nolocal->format($format),libform_get_timezone($timezone));
if($show_local){
	echo "<span class=\"localtime\"><span class=\"localtime-content localtime-content-{$time}\" data-time=\"{$noffset}\"><small>{$time}</small></span></span>";
}
?>
<script>
$(document).ready(function(){
	libforms_timezone_showlocal(".localtime-content-<?php echo $time;?>",'<?php echo elgg_echo("libforms:local_time")?>');
});
</script>
