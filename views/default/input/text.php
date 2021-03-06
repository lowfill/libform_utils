<?php
/**
 * Elgg text input
 * Displays a text input field
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
 */


if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-text {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-text";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$vars = libform_format_attributes($vars,'text');
?>
<input type="text" <?php echo elgg_format_attributes($vars); ?> />
