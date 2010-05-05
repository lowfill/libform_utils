<?php
/**
 * Elgg pulldown input
 * Displays a pulldown input field
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
 * @uses $vars['options'] An array of strings representing the options for the pulldown field
 * @uses $vars['options_values'] An associative array of "value" => "option" where "value" is an internal name and "option" is
 * 								 the value displayed on the button. Replaces $vars['options'] when defined.
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-pulldown";
}

$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}

if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
    $class.=" $validators";
}

$values = $vars['value'];
if(!is_array($values)){
    $values = array($values);
}

$multiple = "";
if(array_key_exists('multiple',$vars) && $vars['multiple']===true){
    $multiple="multiple=\"multiple\"";
    $vars['internalname'].="[]";
}

$size = "";
if(array_key_exists('size',$vars) && $vars['size']){
    $size="size=\"{$vars['size']}\"";
}

?>

<select name="<?php echo $vars['internalname']; ?>"
        <?php echo "id=\"{$internalid}\""; ?>
        <?php echo $vars['js']; ?>
        <?php if ($vars['disabled']) echo ' disabled="yes" '; ?>
        class="<?php echo $class; ?>"
        <?php echo $multiple;?>
        <?php echo $size;?>>
<?php
if ($vars['options_values']) {
	foreach($vars['options_values'] as $value => $option) {
		if (!in_array($value,$values)) {
			echo "<option value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		} else {
			echo "<option value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		}
	}
} else {
	foreach($vars['options'] as $option) {
		if (!in_array($value,$values)) {
			echo "<option>". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		} else {
			echo "<option selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		}
	}
}
?>
</select>