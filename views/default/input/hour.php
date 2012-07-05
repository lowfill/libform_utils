<?php
/**
 * Elgg hour input
 * Displays a hour field powered by calendrical jquery's plugin https://github.com/tobico/jquery-calendrical
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Curverider Ltd
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['disabled'] If true then control is read-only
 * @uses $vars['class'] Class override
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 * @uses $vars[<calendrical_options>] @see https://github.com/tobico/jquery-calendrical
 */


elgg_extend_view("metatags","jquery/calendrical");

if (isset($vars['class'])) {
	$class = $vars['class'];
} else {
	$class = "input-text";
}

$disabled = false;
if (isset($vars['disabled'])) {
	$disabled = $vars['disabled'];
}

$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}

$calendrical_options = array('defaultTime','minTime','maxTime','timeInterval');
$calendrical_values = array();

if(array_key_exists('value', $vars)){
	$vars['defaultTime']=$vars['value'];
}
foreach($vars as $key=>$value){
	if(in_array($key,$calendrical_options)){
		$calendrical_values[$key]=$value;
	}
}
if(!empty($calendrical_values)){
	$calendrical_values = json_encode($calendrical_values);
}
$function = "calendricalTime";
$selectors = "#".$vars['internalname'];

if($vars['end_hour_selector']){
	$function = "calendricalTimeRange";
	$selectors.=",#{$vars['end_hour_selector']}";
}

?>

<input type="text" <?php if ($disabled) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class ?>"/>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("<?php echo $selectors;?>").<?php echo $function?>(<?php echo $calendrical_values?>);
});
</script>