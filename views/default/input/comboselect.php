<?php
/**
 * This view transform a simple select in a comboselect view
 *
 * @package ElggLibFormUtils
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 */

elgg_load_css("libform:css");
elgg_load_css("libform:comboselect");
elgg_load_js("libform:comboselect");

$selected_values = array();
if(!is_array($vars["value"])){
  $selected_values = explode(",",$vars["value"]);
}
else{
  $selected_values = $vars["value"];
}
unset($vars['value']);

$options_values = $vars['options_values'];
unset($vars['options_values']);

$options = $vars['options'];
unset($vars['options']);

$defaults = array(
	'size'=>'6',
	'multiple'=>true
);

$vars = array_merge($defaults,$vars);

$vars = libform_format_attributes($vars,'comboselect');
?>
<select <?php echo elgg_format_attributes($vars); ?>>
<?php
	if ($options_values)
	{
		foreach($options_values as $value => $option) {
	        if (!in_array($value,$selected_values)) {
	            echo "<option value=\"$value\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        } else {
	            echo "<option value=\"$value\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        }
	    }
	}
	else
	{
	    foreach($options as $option) {
	        if (!in_array($option,$selected_values)) {
	            echo "<option value=\"$option\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        } else {
	            echo "<option value=\"$option\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        }
	    }
	}
?>
</select>
<div class="clearfloat"> </div>
<script type="text/javascript">
  jQuery(document).ready(function(){
	 $('#<?php echo $vars['internalid']?>').comboselect({ sort: 'right'});
  });
</script>
