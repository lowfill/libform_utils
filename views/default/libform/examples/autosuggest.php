<?php


//FIXME Make a better examples page

echo "<form>";
echo "<h2>".elgg_echo("libforms:example:autosuggest")."</h2>";
echo elgg_view('input/autosuggest',array('internalname'=>'group_id',
                                            'internalid'=>'group_id',
                                            'suggest'=>'users',
                                            'minChars'=>2,
                                            'tokenLimit'=>1));
echo "</form>";

?>

