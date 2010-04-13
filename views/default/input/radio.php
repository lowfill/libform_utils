<?php
/**
 * Elgg radio input
 * Displays a radio input field
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
 * @uses $vars['options'] An array of strings representing the options for the radio field as "label" => option
 * @uses $vars['separator']
 * @uses $vars['validate'] The validator rules
 * @uses $vars['validate_messages'] The custom validator messages
 */

$class = $vars['class'];
if (!$class) {
    $class = "input-radio";
}
if(isset($vars['validate'])){
    $validators = libform_get_validators($vars['validate']);
}
$i=0;
foreach($vars['options'] as $label => $option) {
    if (strtolower($option) != strtolower($vars['value'])) {
        $selected = "";
    } else {
        $selected = "checked = \"checked\"";
    }
    if($i==0 && !empty($validators)){
        $class.=" $validators";
    }
    $labelint = (int) $label;
    if ("{$label}" == "{$labelint}") {
        $label = $option;
    }

    if (isset($vars['internalid'])) {
        $id = "id=\"{$vars['internalid']}\"";
    }
    if ($vars['disabled']) {
        $disabled = ' disabled="yes" ';
    }
    $internalid = $vars['internalid'];
    if(empty($internalid)){
        $internalid = $vars['internalname'];
    }
    $separator = "<br />";
    if(!empty($vars['separator'])){
        $separator=$vars['separator'];
    }
    $i++;
    echo "<label><input type=\"radio\" $disabled {$vars['js']} name=\"{$vars['internalname']}\" $internalid value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" {$selected} class=\"$class\" />{$label}</label>$separator";
}
echo "<label for=\"{$vars['internalname']}\" class=\"error\">{$vars['validate_messages']}</label>";