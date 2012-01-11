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

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-plaintext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-plaintext";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);

$internalid = $vars['internalid'];
if(empty($internalid)){
    $vars['internalid'] = $vars['internalname'];
}

if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
    $vars['class'].=" $validators";
}

?>

<textarea <?php echo elgg_format_attributes($vars); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false); ?>
</textarea>
