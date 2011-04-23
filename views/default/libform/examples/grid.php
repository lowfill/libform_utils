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

echo "</p><p>Groups";
$column_config = array();
$column_config[]=array('display'=>'GUID',
	'name'=>'guid',
	'width'=> 80,
	'sortable'=>true,
	'align'=>'center'
);
$column_config[]=array('display'=>'Group name',
	'name'=>'name',
	'width'=> 180,
	'sortable'=>false,
	'align'=>'center'
);
$column_config[]=array('display'=>'Group description',
	'name'=>'description',
	'width'=> 280,
	'sortable'=>false,
	'align'=>'center'
);

echo elgg_view('output/grid',array('internalname'=>'grid2',
                                   'endpoint'=>'groups',
								   'column_configuration'=>$column_config,
								   'width'=>600));

echo "</p></form>";

?>

