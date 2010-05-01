<?php
/**
 * Elgg long text input
 * Displays a long text input field
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Curverider Ltd
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['value'] The current value, if any - will be html encoded
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['internalid'] The id of the input field
 * @uses $vars['class'] CSS class
 * @uses $vars['disabled'] Is the input field disabled?
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

$class = "input-textarea";
if (isset($vars['class'])) {
	$class = $vars['class'];
}

$disabled = false;
if (isset($vars['disabled'])) {
	$disabled = $vars['disabled'];
}

$value = '';
if (isset($vars['value'])) {
	$value = $vars['value'];
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

<textarea class="<?php echo $class; ?>" name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> <?php if ($disabled) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?>><?php echo htmlentities($value, ENT_QUOTES, 'UTF-8'); ?></textarea>