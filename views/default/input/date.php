<?php
/**
 * Elgg date input
 * Displays a date input field powered by jquery ui datepicker
 * Use datepicker options names to configure the behavior  
 *
 * @package ElggLibFormUtils
 * @subpackage Core
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
 * @uses $vars[<datepicker_options>] @see http://jqueryui.com/demos/datepicker/ configuration options
 */


elgg_extend_view("metatags","jquery/datepicker");

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

$datepicker_options = array("dateFormat"=>"mm/dd/yy",
							"autoSize"=>false,
							"changeMonth"=>false,
							"changeYear"=>false,
							"minDate"=>"today",
							"maxDate"=>null,
							"numberOfMonths"=>1,
							"selectOtherMonths"=>false);

foreach($vars as $key=>$value){
	if(array_key_exists($key,$datepicker_options)){
		$datepicker_options[$key]=$value;
	}
}
$vars['validate'].=";date";

if(isset($vars['validate'])){
	$validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
	$class.=" $validators";
}

?>

<input type="text" <?php if ($disabled) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class ?>"/>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#<?php echo $vars['internalname'];?>").datepicker(<?php echo json_encode($datepicker_options)?>);
});
</script>