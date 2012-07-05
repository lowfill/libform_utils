<?php
/**
 * Searchfilter field for Elgg
 *
 * This kind of field could bu useful for show similar items available on the workspace
 *
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright 2010
 *
 * @uses $vars['suggest'] To specify what kind of suggestion you wants
 */

elgg_extend_view("metatags","jquery/suggestbox");

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
            $suggest_url=$vars['url']."pg/suggest/{$vars['suggest']}/{$vars['extra_param']}";
    }
}

$search_filter_defaults = array(
	"searchDelay"=>"300",
	"minChars"=>"1",
	"method"=>"GET",
	"contentType"=>"json",
	"queryParam"=>"q",
	"onResult"=>null,
	"target_box"=> $vars['internalname']."-suggestBox-target",
	"target_box_style"=>"",
	"target_box_title"=>""
);

foreach($vars as $key=>$value){
	if(array_key_exists($key,$search_filter_defaults)){
		$search_filter_defaults[$key]=$value;
	}
}

$opts = json_encode($search_filter_defaults);

?>
<div id="<?php echo $vars['internalname']?>-suggestBox-target" class="suggestBox-box" style="display:none">
</div>


<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#<?php echo $vars['internalname']?>").suggestBox("<?php echo $suggest_url?>",<?php echo $opts?>);
});
</script>
