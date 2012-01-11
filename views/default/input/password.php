<?php
/**
 * Elgg password input
 * Displays a password input field
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

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-password {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-password";
}

$defaults = array(
	'disabled' => false,
	'value' => '',
);

$vars = libform_format_attributes($vars,'password');

$attrs = array_merge($defaults, $vars);
?>

<input type="password" <?php echo elgg_format_attributes($attrs); ?> />
