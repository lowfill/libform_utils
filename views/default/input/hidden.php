<?php
/**
 * Create a hidden data field
 * Use this view for forms rather than creating a hidden tag in the wild as it provides
 * extra security which help prevent CSRF attacks.
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

$vars = libform_format_attributes($vars);
?>
<input type="hidden" <?php echo elgg_format_attributes($vars); ?> />