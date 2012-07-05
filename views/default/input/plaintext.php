<?php
/**
 * Elgg long text input (plaintext)
 * Displays a long text input field that should not be overridden by wysiwyg editors.
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
 *
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-textarea";
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

<textarea class="<?php echo $class; ?>" name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?>><?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?></textarea>