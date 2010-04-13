<?php
elgg_extend_view("metatags","jquery/validate");
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#<?php echo $vars['internalname']?>").validate();
});
</script>
