<?php
/**
 * Time related fields examples page
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */
?>
<script type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() { alert("submitted!"); }
});
</script>

<?php
$body ="<h2>".elgg_echo("libforms:example:date")."</h2>";

$body.='<p>Defaults';
$body.=elgg_view('input/date',array('internalname'=>'date1'));
$body.="</p>";

$body.='<p>Change year, change month';
$body.=elgg_view('input/date',array('internalname'=>'date2','changeMonth'=>true,"changeYear"=>true));
$body.="</p>";

$body.='<p>Number of months';
$body.=elgg_view('input/date',array('internalname'=>'date3','numberOfMonths'=>2,'selectOtherMonths'=>true));
$body.="</p>";

$body.="<h2>".elgg_echo("libforms:example:hour")."</h2>";

$body.='<p>Defaults';
$body.=elgg_view('input/hour',array('internalname'=>'hour1'));
$body.="</p>";

$body.='<p>Min time, max time';
$body.=elgg_view('input/hour',array('internalname'=>'hour2','minTime'=>array("hour"=>10,'minute'=>0),"maxTime"=>array("hour"=>"15",'minute'=>0)));

$body.="</p>";

$body.='<p>Time range';
$body.=elgg_view('input/hour',array('internalname'=>'hour3','end_hour_selector'=>'hour4',));
$body.=elgg_view('input/text',array('internalname'=>'hour4'));
$body.="</p>";

$body.="<h2>".elgg_echo("libforms:example:timezone")."</h2>";

$body.='<p>Defaults';
$body.=elgg_view('input/timezone',array('internalname'=>'timezone1'));
$body.="</p>";

echo elgg_view('input/form',array('internalname'=>"validator",
                                  'body'=>$body,
                                  'validate'=>true));
?>
<script>
jQuery(document).ready(function(){
	jQuery("#validator").submit(function(e){
		e.preventDefault();
	});
});
</script>