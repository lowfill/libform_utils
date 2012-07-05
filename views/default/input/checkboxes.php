<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
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
 * @uses $vars['options'] An array of strings representing the label => options for the checkbox field
 * @uses $vars['separator'] Separator to be used '<br>' by default
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

<<<<<<< HEAD
$class = (isset($vars['class'])) ? $vars['class'] : 'input-checkboxes';
$value = (isset($vars['value'])) ? $vars['value'] : NULL;
$value_array = (is_array($value)) ? array_map('strtolower', $value) : array(strtolower($value));
$internalname = (isset($vars['internalname'])) ? $vars['internalname'] : '';
$options = (isset($vars['options']) && is_array($vars['options'])) ? $vars['options'] : array();
$default = (isset($vars['default'])) ? $vars['default'] : 0;

$id = (isset($vars['internalid'])) ? $vars['internalid'] : '';
$disabled = (isset($vars['disabled'])) ? $vars['disabled'] : FALSE;
$js = (isset($vars['js'])) ? $vars['js'] : '';
if(isset($vars['validate'])){
	$validators = libform_get_validators($vars['validate']);
}

if ($options) {
	// include a default value so if nothing is checked 0 will be passed.
	if ($internalname) {
		echo "<input type=\"hidden\" name=\"$internalname\" value=\"$default\">";
	}

	$i=0;
	foreach($options as $label => $option) {
		// @hack - This sorta checks if options is not an assoc array and then
		// ignores the label (because it's just the index) and sets the value ($option)
		// as the label.
		// Wow.
		// @todo deprecate in Elgg 1.8
		if (is_integer($label)) {
			$label = $option;
		}

		if (!in_array(strtolower($option), $value_array)) {
			$selected = FALSE;
		} else {
			$selected = TRUE;
		}

		$attr = array(
			'type="checkbox"',
			'value="' . htmlentities($option, ENT_QUOTES, 'UTF-8') . '"'
			);

			if ($id) {
				$attr[] = "id=\"$id\"";
			}

			if($i==0 && !empty($validators)){
				$class.=" $validators";
			}
			if ($class) {
				$attr[] = "class=\"$class\"";
			}

			if ($disabled) {
				$attr[] = 'disabled="yes"';
			}

			if ($selected) {
				$attr[] = 'checked = "checked"';
			}

			if ($js) {
				$attr[] = $js;
			}

			if ($internalname) {
				// @todo this really, really should only add the []s if there are > 1 element in options.
				$attr[] = "name=\"{$internalname}[]\"";
			}
			$separator = "<br />";
			if(!empty($vars['separator'])){
				$separator = $vars['separator'];
			}
				
			$attr_str = implode(' ', $attr);
			$i++;
			echo "<label><input $attr_str />$label</label>$separator";
	}
}

=======
$class = $vars['class'];
if (!$class) {
    $class = "input-checkboxes";
}
if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate']);
}
$i=0;

foreach($vars['options'] as $label => $option) {
    //if (!in_array($option,$vars['value'])) {
    if (is_array($vars['value'])) {
        $valarray = $vars['value'];
        $valarray = array_map('strtolower', $valarray);
        if (!in_array(strtolower($option),$valarray)) {
            $selected = "";
        } else {
            $selected = "checked = \"checked\"";
        }
    } else {
        if (strtolower($option) != strtolower($vars['value'])) {
            $selected = "";
        } else {
            $selected = "checked = \"checked\"";
        }
    }
    if($i==0 && !empty($validators)){
        $class.=" $validators";
    }
    $labelint = (int) $label;
    if ("{$label}" == "{$labelint}") {
        $label = $option;
    }

    $internalid = $vars['internalid'];
    if(empty($internalid)){
        $internalid = $vars['internalname'];
    }
    $disabled = "";
    if ($vars['disabled']){
        $disabled = ' disabled="yes" ';
    }
    $separator = "<br />";
    if(!empty($vars['separator'])){
        $separator = $vars['separator'];
    }
    echo "<label><input type=\"checkbox\" $internalid $disabled {$vars['js']} name=\"{$vars['internalname']}[]\" value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" {$selected} class=\"$class\" />{$label}</label>$separator";
}
>>>>>>> 6866a794580b5426697147563d01187d0813e938
echo "<label for=\"{$vars['internalname']}[]\" class=\"error\">{$vars['validate_messages']}</label>";