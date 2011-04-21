<?php
/**
 * Libforms_utils general views controller
 *
 *
 * @author Diego Andrés Ramírez Aragón
 * @copyright 2010
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;

$title = elgg_echo("libforms:example:title");
$body =  elgg_view_title($title).elgg_view('page_elements/contentwrapper',array('body'=>elgg_view("libform/examples")));

$body = elgg_view_layout('two_column_left_sidebar', '', $body);

// Finally draw the page
page_draw($title, $body);
?>