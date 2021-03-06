<?php
/**
 * Improved Elgg access level input overwrites 'views/default/input/access.php'
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
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

$class = "input-access";
if (isset($vars['class'])) {
	$class = $vars['class'];
}

$disabled = false;
if (isset($vars['disabled'])) {
	$disabled = $vars['disabled'];
}

if (!array_key_exists('value', $vars) || $vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = get_default_access();
}


if ((!isset($vars['options'])) || (!is_array($vars['options']))) {
	$vars['options'] = array();
	$vars['options'] = get_write_access_array();
}

$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}

if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
    $class.=" $validators";
}

if (is_array($vars['options']) && sizeof($vars['options']) > 0) {
	?>

	<select <?php echo "id=\"{$internalid}\""; ?> name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['js'])) echo $vars['js']; ?> <?php if ($disabled) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
	<?php

	foreach($vars['options'] as $key => $option) {
		if ($key != $vars['value']) {
			echo "<option value=\"{$key}\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		} else {
			echo "<option value=\"{$key}\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
		}
	}

	?>
	</select>
	<?php
}