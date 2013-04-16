<?php
/**
 * Loads the validation framework
 *
 * @package ElggLibFormUtils
 * @subpackage Core
 * @author Diego Andrés Ramírez Aragón
 * @link http://github.com/lowfill/libform_utils
 *
 * @uses $vars['iternalname'] Form's name to be validated
 */

elgg_load_css("libform:css");
elgg_load_js("libform:validator");
elgg_load_js("libform:validator:aditional");
elgg_load_js("libform:validator:metadata");
elgg_load_js("libform:validator:i18n");

if(!array_key_exists('internalname', $vars)){
	$action = str_replace(elgg_get_config('url'),'',$vars['action']);
	$vars['internalname'] = 'elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $action);
}

?>
<script type="text/javascript">
$(document).ready(function() {
	$("#<?php echo $vars['internalname']?>").validate();
});
</script>
