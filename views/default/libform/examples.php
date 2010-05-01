<?php
/**
 * Libform util examples page
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */


global $CONFIG;

$option = get_input('option','comboselect');
$selected="class=\"selected\"";
if(!empty($option)){
    $var="{$option}_selected";
    $$var=$selected;
}
?>

<div id="elgg_horizontal_tabbed_nav">
  <ul>
  <li><a href="<?php echo $vars['url']."pg/libform/?option=comboselect";?>" <?php echo $comboselect_selected?>><?php echo elgg_echo("libform:comboselect");?></a></li>
  <li><a href="<?php echo $vars['url']."pg/libform/?option=autosuggest";?>" <?php echo $autosuggest_selected?>><?php echo elgg_echo("libform:autosuggest");?></a></li>
  <li><a href="<?php echo $vars['url']."pg/libform/?option=validate";?>" <?php echo $validate_selected?>><?php echo elgg_echo("libform:validate");?></a></li>
  <li><a href="<?php echo $vars['url']."pg/libform/?option=location";?>" <?php echo $location_selected?>><?php echo elgg_echo("libform:location");?></a></li>
  </ul>
</div>
<?php
    echo elgg_view("libform/examples/{$option}",$vars);
?>
