<?php
/**
 * Libforms_utils general views controller
 *
 *
 * @author Diego Andrés Ramírez Aragón
 * @copyright 2010
 */


$title = elgg_echo("libforms:example:title");

$vars = array('title'=>$title,
			  'filter'=>false,
			  'content'=>elgg_view("libform/examples"));

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
