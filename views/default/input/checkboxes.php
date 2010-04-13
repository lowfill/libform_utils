<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['options'] An array of strings representing the label => options for the checkbox field
 * @uses $vars['separator'] Separator to be used '<br>' by default
 */

$class = $vars['class'];
if (!$class) {
    $class = "input-checkboxes";
}

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