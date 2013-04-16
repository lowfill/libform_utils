<?php
/**
 * Elgg tag input
 * Displays a tag input field
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Curverider Ltd
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['value'] The current value, if any - string or array - tags will be encoded
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['internalid'] The id of the input field
 * @uses $vars['class'] CSS class override
 * @uses $vars['disabled'] Is the input field disabled?
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-tags {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-tags";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->tags;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

if (is_array($vars['value'])) {
	$tags = array();

	foreach ($vars['value'] as $tag) {
		if (is_string($tag)) {
			$tags[] = $tag;
		} else {
			$tags[] = $tag->value;
		}
	}

	$vars['value'] = implode(", ", $tags);
}

$vars = libform_format_attributes($vars,'tags');

?>
<input type="text" <?php echo elgg_format_attributes($vars); ?> />
