<?php
/**
 * Elgg email input
 * Displays an email input field
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
	$vars['class'] = "elgg-input-email {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-email";
}

$vars['validate'] = "email;{$vars['validate']}";

echo elgg_view("input/text",$vars);
