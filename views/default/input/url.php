<?php
/**
 * Elgg URL input overwrite 'views/input/url.php'
 * Displays a URL input field and if the validation framework is active valid if is a valid URL
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
	$vars['class'] = "elgg-input-url {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-url";
}

$vars["validate"] = "url;".$vars["validate"];

echo elgg_view("input/text",$vars);
