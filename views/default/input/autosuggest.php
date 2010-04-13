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
 */
extend_view("metatags","jquery/autosuggest");

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
            $suggest_url=$vars['suggest'];
    }
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
                "tokenList"=>"token-input-list-facebook",
                "token"=>"token-input-token-facebook",
                "tokenDelete"=>"token-input-delete-token-facebook",
                "selectedToken"=>"token-input-selected-token-facebook",
                "highlightedToken"=>"token-input-highlighted-token-facebook",
                "dropdown"=>"token-input-dropdown-facebook",
                "dropdownItem"=>"token-input-dropdown-item-facebook",
                "dropdownItem2"=>"token-input-dropdown-item2-facebook",
                "selectedDropdownItem"=>"token-input-selected-dropdown-item-facebook",
                "inputToken"=>"token-input-input-token-facebook"
            )
);

foreach($vars as $key=>$value){
    if(array_key_exists($key,$auto_suggest_defaults)){
        $auto_suggest_defaults[$key]=$value;
    }
}
$opts = json_encode($auto_suggest_defaults);
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#<?php echo $vars['internalname']?>").tokenInput("<?php echo $suggest_url?>",<?php echo $opts?>);
});
</script>
