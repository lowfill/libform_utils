<?php
/**
 * Autosuggest field for Elgg
 *
 * The 'value' property of the object would be replaced by a coma separated values list
 *
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright 2010
 *
 * @uses $vars['suggest'] To specify what kind of suggestion you wants
 * @uses $vars['style'] To specify the style of field to be used
 */

elgg_extend_view("metatags","jquery/autosuggest");

if(!empty($vars['value'])){
    $field_value = $vars['value'];
    $vars['value']="";
}
echo elgg_view('input/text',$vars);

if(array_key_exists('suggest',$vars)){
    switch($vars['suggest']){
        case "users":
            $suggest_url=$vars['url']."pg/suggest/user";
            break;
        case "groups":
            $suggest_url=$vars['url']."pg/suggest/group";
            break;
        default:
            $suggest_url=$vars['url']."pg/suggest/{$vars['suggest']}";
    }
}
$style = "";
if(!empty($vars['style'])){
    $style=$vars['style'];
}
//TODO i18n
$auto_suggest_defaults=array(
    "hintText"=>"Type in a search term",
    "noResultsText"=>"No results",
    "searchingText"=>"Searching...",
    "searchDelay"=>300,
    "minChars"=>1,
    "tokenLimit"=>null,
    "jsonContainer"=>null,
    "method"=>"GET",
    "contentType"=>"json",
    "queryParam"=>"q",
    "onResult"=>null,
    "classes"=>array(
                "tokenList"=>"token-input-list{$style}",
                "token"=>"token-input-token{$style}",
                "tokenDelete"=>"token-input-delete-token{$style}",
                "selectedToken"=>"token-input-selected-token{$style}",
                "highlightedToken"=>"token-input-highlighted-token{$style}",
                "dropdown"=>"token-input-dropdown{$style}",
                "dropdownItem"=>"token-input-dropdown-item{$style}",
                "dropdownItem2"=>"token-input-dropdown-item2{$style}",
                "selectedDropdownItem"=>"token-input-selected-dropdown-item{$style}",
                "inputToken"=>"token-input-input-token{$style}"
            )
);

foreach($vars as $key=>$value){
    if(array_key_exists($key,$auto_suggest_defaults)){
        $auto_suggest_defaults[$key]=$value;
    }
}

if(!empty($field_value)){
    $values = explode(",",$field_value);
    $prepopulate = array();
    foreach($values as $value){
        $fields = explode("||",$value);
        if(count($fields)==1){
            $fields[]=elgg_echo($fields[0]);
        }
       $prepopulate[] = array("id"=>$fields[0],"name"=>$fields[1]);
    }
    $auto_suggest_defaults['prePopulate']=$prepopulate;
}
$opts = json_encode($auto_suggest_defaults);
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#<?php echo $vars['internalname']?>").tokenInput("<?php echo $suggest_url?>",<?php echo $opts?>);
});
</script>
