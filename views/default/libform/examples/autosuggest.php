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
echo "<h2>".elgg_echo("libforms:example:autosuggest")."</h2>";
echo "<p>Users";
echo elgg_view('input/autosuggest',array('internalname'=>'user_id',
                                            'internalid'=>'user_id',
                                            'suggest'=>'users',
                                            'minChars'=>2,
                                            'tokenLimit'=>1));
echo "</p><p>Groups";
echo elgg_view('input/autosuggest',array('internalname'=>'group_id',
                                            'internalid'=>'group_id',
                                            'suggest'=>'groups',
                                            'minChars'=>2,
                                            'tokenLimit'=>1));

echo "</p><p>Groups (Facebook)";
echo elgg_view('input/autosuggest',array('internalname'=>'group_id-fb',
                                            'internalid'=>'group_id-fb',
                                            'suggest'=>'groups',
                                            'minChars'=>2,
                                            'style'=>'-facebook'));

echo "</p></form>";

?>

