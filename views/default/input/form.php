<?php
/**
 * Create a form for data submission.
 * Use this view for forms rather than creating a form tag in the wild as it provides
 * extra security which help prevent CSRF attacks.
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Curverider Ltd
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['body'] The body of the form (made up of other input/xxx views and html
 * @uses $vars['method'] Method (default POST)
 * @uses $vars['enctype'] How the form is encoded, default blank
 * @uses $vars['action'] URL of the action being called
 * @uses $vars['js'] Any Javascript to enter into the form
 * @uses $vars['internalid'] id for the form for CSS/Javascript
 * @uses $vars['internalname'] name for the form for Javascript
 * @uses $vars['disable_security'] turn off CSRF security by setting to true
 * @uses $vars['validate'] If you wants this form to be validated
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-form {$vars['class']}";
} else {
	$vars['class'] = 'elgg-form';
}

//FIXME Add a name to the form even if not provided by user
$defaults = array(
	'method' => "post",
	'disable_security' => FALSE,
	'internalname'=>''
);

$vars = array_merge($defaults, $vars);

$vars['action'] = elgg_normalize_url($vars['action']);
$vars['method'] = strtolower($vars['method']);

$body = $vars['body'];
unset($vars['body']);
// Generate a security header
if (!$vars['disable_security']) {
	$body = elgg_view('input/securitytoken') . $body;
}
unset($vars['disable_security']);

if (isset($vars['internalid'])) {
	$vars['internalid'] = $vars['internalid'];
}
else{
	$vars['internalid'] = $vars['internalname'];
}


$attributes = elgg_format_attributes($vars);
echo "<form $attributes><fieldset>$body</fieldset></form>";
if($vars['validate']){
	echo elgg_view('input/validator',$vars);
}
