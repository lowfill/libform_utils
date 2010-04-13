<?php
/**
 * Custom styles
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */
?>

.block { display: block; }
label.error {
	display: none;
	/* remove the next line when you have trouble in IE6 with labels in list */
	color: red;
	font-style: italic;
	font-size: 13px;
	margin-left:5px;
}
div.error { display: none; }
input:focus { border: 1px dotted black; }
input.error { border: 1px dotted red; }
textarea.error { border: 1px dotted red; }
