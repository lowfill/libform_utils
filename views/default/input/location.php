<?php

elgg_extend_view("metatags","jquery/location");

$countries = libform_get_countries();
$default_option = array(""=>elgg_echo("libform:choose_one"));
$countries = array_merge($default_option,$countries);

$name = $vars['internalname'];

$country_value = "";
if(!empty($vars["country"])){
    $country_value = $vars['country'];
}

$state_value = "";
if(!empty($vars["state"])){
    $state_value = $vars['state'];
}

$city_value = "";
if(!empty($vars["city"])){
    $city_value = $vars['city'];
}

$postal_code_value = "";
if(!empty($vars["postal_code"])){
    $postal_code_value = $vars['postal_code'];
}

?>

<div class="location">
<?php echo elgg_echo('libform:country')?>:
<?php echo elgg_view('input/pulldown',array('internalname'=>"{$name}_country",
											'options_values'=>$countries,
                                            'value'=>$country_value,
                                            'validate'=>"required"));
?>
<br>
<div class="location_box">
<?php
    echo elgg_view('input/pulldown',array('internalname'=>"{$name}_state",
                                          'class'=>"location_field",
                                          'options_values'=>$default_option,
                                          'validate'=>"required"));
?>&nbsp;&nbsp;
<?php
    echo elgg_view('input/text',array('internalname'=>"{$name}_city",
                                      'class'=>"location_field",
                                      'value'=>$city_value,
                                      ));
?>&nbsp;&nbsp;
<?php
    echo elgg_view('input/text',array('internalname'=>"{$name}_postal_code",
                                      'class'=>"location_field",
                                      'value'=>$postal_code_value));
?>&nbsp;&nbsp;
<br>
<span class="location_state_label"><?php echo elgg_echo("libform:state")?></span>
<span class="location_city_label"><?php echo elgg_echo("libform:city")?></span>
<span class="location_postal_label"><?php echo elgg_echo("libform:postal_code")?></span>
</div>
</div>
<script>
jQuery(document).ready(function(){
	var target = jQuery("#<?php echo $name?>_state");
	jQuery("#<?php echo $name?>_country").change(function(){
		  getCountryInfo(jQuery(this).val(),target,'');
		});
	<?php
	if(!empty($country_value)){
	?>
		getCountryInfo("<?php echo $country_value;?>",target,"<?php echo $state_value?>");
	<?php
	}
    ?>
});
</script>