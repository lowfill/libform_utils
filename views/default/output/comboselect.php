<?php

$value = $vars['value'];
if(is_array($value)){
    $value = array_map(elgg_echo,$value);
    $value = implode(',',$value);
}
echo $value;
?>