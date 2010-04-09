<?php


//FIXME Make a better examples page
$options_values = array(
    "1"=>"Item 1",
    "2"=>"Item 2",
    "3"=>"Item 3",
    "4"=>"Item 4",
);

echo "<form>";
echo "<h2>".elgg_echo("libforms:example:comboselect")."</h2>";
echo elgg_view('input/comboselect',array('internalname'=>"comboselect",'options_values'=>$options_values));

echo "<h2>".elgg_echo("libforms:example:autosuggest")."</h2>";
echo elgg_view('input/autosuggest',array('internalname'=>'group_id',
                                            'internalid'=>'group_id',
                                            'suggest'=>'groups',
                                            'minChars'=>2,
                                            'tokenLimit'=>1));
echo "</form>";

?>

