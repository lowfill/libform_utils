<?php
/**
 * Autosuggest examples page
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */

echo "<form>";
echo "<h2>".elgg_echo("libforms:example:suggestbox")."</h2>";
echo "<p>Users";
echo elgg_view('input/suggestbox',array('internalname'=>'user_id',
                                        'internalid'=>'user_id',
                                        'suggest'=>'users',
                                        'minChars'=>2));

echo elgg_view('input/suggestbox',array('internalname'=>'user_id2',
                                        'internalid'=>'user_id2',
                                        'suggest'=>'users',
										'value'=>'Look for users',
                                        'target_box_title'=>"Similar users",
                                        'minChars'=>2));

echo "</p></form>";

?>

