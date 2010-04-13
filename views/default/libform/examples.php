<?php


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
  </ul>
</div>
<?php
    echo elgg_view("libform/examples/{$option}",$vars);
?>
