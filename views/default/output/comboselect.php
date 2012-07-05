<?php
/**
 * Elgg comboselect output
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */

$value = $vars['value'];

if(!is_array($value)){
    $value = array($value);
}

$value = array_map(elgg_echo,$value);
$value = implode(', ',$value);

echo $value;
?>