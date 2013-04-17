<?php
/**
 * Elgg dropdown display
 * Displays a value that was entered into the system via a dropdown
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['text'] The text to display
 *
 */

if(is_array($vars['value'])){
	$vars['value']=array_map('elgg_echo',$vars['value']);
	$vars['value']=implode(', ',$vars['value']);
}
echo htmlspecialchars($vars['value'], ENT_QUOTES, 'UTF-8', false);
