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
echo "<h2>".elgg_echo("libforms:example:grid")."</h2>";
echo "<p>Users";
echo elgg_view('output/grid',array('internalname'=>'grid'));

echo "</p></form>";

?>

