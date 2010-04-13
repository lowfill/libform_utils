<?php
/**
 * Elgg URL input overwrite 'views/input/url.php'
 * Displays a URL input field and if the validation framework is active valid if is a valid URL
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

$class = $vars['class'];
if (!$class) {
	$class = "input-url";
}
$internalid = $vars['internalid'];
if(empty($internalid)){
    $internalid = $vars['internalname'];
}
$vars["validate"] = "url;".$vars["validate"];
if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate'],$vars['validate_messages']);
    $class.=" $validators";
}

?>

<input type="text" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class; ?>"/>