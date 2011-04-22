<?php
/**
 * Comboselect examples page
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */

$options_values = array(
    "1"=>"Item 1",
    "2"=>"Item 2",
    "3"=>"Item 3",
    "4"=>"Item 4",
);

echo "<form>";
echo "<h2>".elgg_echo("libforms:example:comboselect")."</h2>";
echo elgg_view('input/comboselect',array('internalname'=>"comboselect",'options_values'=>$options_values));

echo "</form>";

?>

