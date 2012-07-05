<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Curverider Ltd
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['internalid'] The id of the input field
 * @uses $vars['class'] CSS class
 * @uses $vars['disabled'] Is the input field disabled?
 * @uses $vars['value'] The current value if any
 * @uses $vars['validate'] The validator rules. 'accept' rule is recomended here
 * @uses $vars['validate_messages'] The custom validator messages
 */

if (!empty($vars['value'])) {
	echo elgg_echo('fileexists') . "<br />";
}

$class = "input-file";
if (isset($vars['class'])) {
	$class = $vars['class'];
}

$disabled = false;
if (isset($vars['disabled'])) {
	$disabled = $vars['disabled'];
}

$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}

if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
    $class.=" $validators";
}

?>
<input type="file" size="30" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> <?php if ($disabled) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>" />