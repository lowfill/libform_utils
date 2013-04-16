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

elgg_deprecated_notice("input/pulldown was deprecated by input/dropdown", 1.8);
echo elgg_view('input/dropdown', $vars);
