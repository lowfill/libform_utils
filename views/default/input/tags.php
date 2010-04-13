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


$class = "input-tags";
if (isset($vars['class'])) {
	$class = $vars['class'];
}

$disabled = false;
if (isset($vars['disabled'])) {
	$disabled = $vars['disabled'];
}

$tags = "";
if (!empty($vars['value'])) {
	if (is_array($vars['value'])) {
		foreach($vars['value'] as $tag) {

			if (!empty($tags)) {
				$tags .= ", ";
			}
			if (is_string($tag)) {
				$tags .= $tag;
			} else {
				$tags .= $tag->value;
			}
		}
	} else {
		$tags = $vars['value'];
	}
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
<input type="text" <?php if ($disabled) echo ' disabled="yes" '; ?><?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php echo "id=\"{$internalid}\""; ?> value="<?php echo htmlentities($tags, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class; ?>"/>