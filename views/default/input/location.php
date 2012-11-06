<?php

$enabled = elgg_get_plugin_setting('location','libform_utils');
error_log('Location:' . $enabled);

if(empty($enabled) || $enabled=='yes'){

	elgg_load_css("libform:css");
	elgg_load_js("libform:location:i18n");
	elgg_load_js("libform:location");

	$countries = libform_get_countries();
	$default_option = array(""=>elgg_echo("libform:choose_one"));
	$countries = array_merge($default_option,$countries);

	$name = $vars['internalname'];
	$value = $vars['value'];
	list($country_value,$state_value,$city_value,$postal_code_value) = explode("||",$value);

	if(!empty($vars["country"])){
		$country_value = $vars['country'];
	}

	if(!empty($vars["state"])){
		$state_value = $vars['state'];
	}

	if(!empty($vars["city"])){
		$city_value = $vars['city'];
	}

	if(!empty($vars["postal_code"])){
		$postal_code_value = $vars['postal_code'];
	}

	$enable_postal_code = false;
	if(!empty($vars["enable_postal_code"])){
		$enable_postal_code = $vars['enable_postal_code'];
	}

	?>

<div class="location" id="location_field_<?php echo $name;?>">
	<?php echo elgg_echo('libform:country')?>
	:
	<?php echo elgg_view('input/dropdown',array('internalname'=>"{$name}_country",
	'options_values'=>$countries,
	'value'=>$country_value,
	'validate'=>"required"));
	?>
	<br>
	<div class="location_box">
		<span class="location_state_label"><?php echo elgg_echo("libform:state")?>
		</span> <span class="location_city_label"><?php echo elgg_echo("libform:city")?>
		</span>
		<?php if($enable_postal_code){?>
		<span class="location_postal_label"><?php echo elgg_echo("libform:postal_code")?>
		</span>
		<?php }?>
		<br>
		<?php
		echo elgg_view('input/dropdown',array('internalname'=>"{$name}_state",
                                          'class'=>"location_field",
                                          'options_values'=>$default_option,
                                          'validate'=>"required"));
?>
		&nbsp;&nbsp;
		<?php
		echo elgg_view('input/text',array('internalname'=>"{$name}_city",
                                      'class'=>"location_field",
                                      'value'=>$city_value,
                                      ));
?>
		&nbsp;&nbsp;
		<?php if($enable_postal_code){?>
		<?php
		echo elgg_view('input/text',array('internalname'=>"{$name}_postal_code",
                                      'class'=>"location_field",
                                      'value'=>$postal_code_value));
?>
		&nbsp;&nbsp;
		<?php }?>
	</div>
</div>
<?php 
echo elgg_view("input/hidden",array('internalname'=>$name,'value'=>$value,'internalid'=>$name));
?>
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
	jQuery(".location_field",jQuery("#location_field_<?php echo $name;?>")).change(function(){
		var value = jQuery("#<?php echo $name?>_country").val()+"||"+jQuery("#<?php echo $name?>_state").val();
		value+="||"+jQuery("#<?php echo $name?>_city").val();
		<?php if($enable_postal_code){?>
		value+="||"+jQuery("#<?php echo $name?>_postal_code").val();
		<?php }?>
		jQuery("#<?php echo $name?>").val(value);
	});
});
</script>
<?php 
}
else{
	if (isset($vars['class'])) {
		$vars['class'] = "elgg-input-location {$vars['class']}";
	} else {
		$vars['class'] = "elgg-input-location";
	}

	$defaults = array(
			'disabled' => false,
	);

	if (isset($vars['entity'])) {
		$defaults['value'] = $vars['entity']->location;
		unset($vars['entity']);
	}

	$vars = array_merge($defaults, $vars);

	echo elgg_view('input/tag', $vars);
}
?>