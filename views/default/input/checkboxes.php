<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
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
 * @uses $vars['options'] An array of strings representing the label => options for the checkbox field
 * @uses $vars['separator'] Separator to be used '<br>' by default
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */


if(isset($vars['validate'])){
	$validators = libform_get_validators($vars['validate']);
}

$defaults = array(
	'align' => 'vertical',
	'value' => array(),
	'default' => 0,
	'disabled' => false,
	'options' => array(),
	'name' => '',
);

$vars = array_merge($defaults, $vars);

$class = "elgg-input-checkboxes elgg-{$vars['align']}";
if (isset($vars['class'])) {
	$class .= " {$vars['class']}";
	unset($vars['class']);
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
	unset($vars['id']);
}

if (is_array($vars['value'])) {
	$values = array_map('elgg_strtolower', $vars['value']);
} else {
	$values = array(elgg_strtolower($vars['value']));
}

$input_vars = $vars;
$input_vars['default'] = false;
if ($vars['name']) {
	$input_vars['name'] = "{$vars['name']}[]";
}
unset($input_vars['align']);
unset($input_vars['options']);

if (count($vars['options']) > 0) {
	// include a default value so if nothing is checked 0 will be passed.
	if ($vars['name'] && $vars['default'] !== false) {
		echo "<input type=\"hidden\" name=\"{$vars['name']}\" value=\"{$vars['default']}\" />";
	}

	echo "<ul class=\"$class\" $id>";
	foreach ($vars['options'] as $label => $value) {
		// @deprecated 1.8 Remove in 1.9
		if (is_integer($label)) {
			elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/checkboxes', 1.8);
			$label = $value;
		}

		$input_vars['checked'] = in_array(elgg_strtolower($value), $values);
		$input_vars['value']   = $value;

		$input = elgg_view('input/checkbox', $input_vars);

		echo "<li><label>$input$label</label></li>";
	}
	echo '</ul>';
}


echo "<label for=\"{$vars['name']}[]\" class=\"error\">{$vars['validate_messages']}</label>";