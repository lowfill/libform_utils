<?php
/**
 * Create a input button overwrites 'views/default/input/button.php'
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
 * @uses $vars['internalid'] The id of the input field
 * @uses $vars['type'] Submit, button, or reset, defaults to submit.
 * @uses $vars['src'] Src of an image
 *
 */

global $CONFIG;

if (isset($vars['class'])) {
	$vars['class'] = "elgg-button {$vars['class']}";
} else {
	$vars['class'] = "elgg-button";
}

$defaults = array(
	'type' => 'button',
);

$vars = array_merge($defaults, $vars);

switch ($vars['type']) {
	case 'button':
	case 'reset':
	case 'submit':
	case 'image':
		break;
	default:
		$vars['type'] = 'button';
		break;
}

// blank src if trying to access an offsite image. @todo why?
if (isset($vars['src']) && strpos($vars['src'], elgg_get_site_url()) === false) {
	$vars['src'] = "";
}

$vars = libform_format_attributes($vars,'button');
?>
<input <?php echo elgg_format_attributes($vars); ?> />
