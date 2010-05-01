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


echo "<form>";
echo "<h2>".elgg_echo("libforms:example:location")."</h2>";
echo elgg_view('input/location',array('internalname'=>"location"));
echo "<div class=\"clearfloat\"></div>";
echo elgg_view('input/location',array('internalname'=>"location_values",
                                      'country'=>"US",
                                      'state'=>"Alaska",
                                      'city'=>"Yukon",
                ));

echo "</form>";

?>

