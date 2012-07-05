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
<<<<<<< HEAD
echo "<div class=\"clearfloat\"></div><br>";
=======
echo "<div class=\"clearfloat\"></div>";
>>>>>>> 6866a794580b5426697147563d01187d0813e938
echo elgg_view('input/location',array('internalname'=>"location_values",
                                      'country'=>"US",
                                      'state'=>"Alaska",
                                      'city'=>"Yukon",
                ));
<<<<<<< HEAD
echo "<div class=\"clearfloat\"></div><br>";

echo elgg_view('input/location',array('internalname'=>"location_value",
                                      'value'=>"US||Alaska||Yukon"));
                
=======

>>>>>>> 6866a794580b5426697147563d01187d0813e938
echo "</form>";

?>

