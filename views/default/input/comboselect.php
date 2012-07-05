<?php
/**
 * This view transform a simple select in a comboselect view
 *
 * @package ElggLibFormUtils
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 */

elgg_extend_view("metatags","jquery/comboselect");

//FIXME Implements automatic validator for this kind of field
if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
}

$selected_values = array();
if(!is_array($vars["value"])){
  $selected_values = explode(",",$vars["value"]);
}
else{
  $selected_values = $vars["value"];
}

$size = (!empty($vars["size"]))?$vars["size"]:6;

$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}

?>
<select id="<?php echo $internalid; ?>" name="<?php echo $vars['internalname']; ?>[]" multiple="multiple" size="<?php echo $size; ?>" <?php echo $vars['js']; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php
	if ($vars['options_values'])
	{
		foreach($vars['options_values'] as $value => $option) {
	        if (!in_array($value,$selected_values)) {
	            echo "<option value=\"$value\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        } else {
	            echo "<option value=\"$value\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
	        }
	    }
	}
	else
	{
	    foreach($vars['options'] as $option) {
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
	 $('#<?php echo $internalid?>').comboselect({ sort: 'right'});
  });
</script>
