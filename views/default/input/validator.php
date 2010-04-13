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

elgg_extend_view("metatags","jquery/validate");
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#<?php echo $vars['internalname']?>").validate();
});
</script>
