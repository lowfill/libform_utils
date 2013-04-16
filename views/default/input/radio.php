<?php
/**
 * Elgg radio input
 * Displays a radio input field
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
 * @uses $vars['options'] An array of strings representing the options for the radio field as "label" => option
 * @uses $vars['separator']
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */
$defaults = array(
	'align' => 'vertical',
	'value' => array(),
	'disabled' => false,
	'options' => array(),
	'name' => '',
);

$vars = array_merge($defaults, $vars);
$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
	unset($vars['id']);
}

$class = "elgg-input-radios elgg-{$vars['align']}";
if (isset($vars['class'])) {
	$class .= " {$vars['class']}";
	unset($vars['class']);
}
unset($vars['align']);
$vars['class'] = 'elgg-input-radio';

if (is_array($vars['value'])) {
	$vars['value'] = array_map('elgg_strtolower', $vars['value']);
} else {
	$vars['value'] = array(elgg_strtolower($vars['value']));
}

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

$i=0;
$validate_messages = $vars['validate_messages'];
if ($options && count($options) > 0) {
	echo "<ul class=\"$class\" $id>";
	foreach ($options as $label => $option) {

		$vars['checked'] = in_array(elgg_strtolower($option), $value);
		$vars['value'] = $option;

		$vars = libform_format_attributes($vars,'radio');
		
		$attributes = elgg_format_attributes($vars);

		// handle indexed array where label is not specified
		// @deprecated 1.8 Remove in 1.9
		if (is_integer($label)) {
			elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/radio', 1.8);
			$label = $option;
		}

		echo "<li><label><input type=\"radio\" $attributes />$label</label></li>";
	}
	echo '</ul>';
}
echo "<label for=\"{$vars['internalname']}\" class=\"error\">{$validate_messages}</label>";
