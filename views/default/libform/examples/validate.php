<?php
/**
 * Validation framework examples page
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
$body ="<h2>".elgg_echo("libforms:example:validate")."</h2>";
$body.='<p>Required';
$body.=elgg_view('input/text',array('internalname'=>'text1',
                                  'validate'=>'required'));
$body.="</p>";


$body.='<p>Required, minlength, maxlength';
$body.=elgg_view('input/text',array('internalname'=>'text3',
                                  'validate'=>'required;minlength:3;maxlength:10'));
$body.="</p>";

$body.='<p>Min, custom message';
$body.=elgg_view('input/text',array('internalname'=>'text5',
                                  'validate'=>'required;min:3',
                                  'validate_messages'=>"required:'Please fill this field!',min:'Fill with something greater than 3'"));
$body.="</p>";

$body.='<p>URL';
$body.=elgg_view('input/url',array('internalname'=>'text6'));
$body.="</p>";

$body.='<p>Email';
$body.=elgg_view('input/email',array('internalname'=>'text2'));
$body.="</p>";

$body.='<p>Password';
$body.=elgg_view('input/password',array('internalname'=>'pass',
									 'validate'=>'required;rangelength:[4,8]'));
$body.="</p>";

$body.='<p>Password 2';
$body.=elgg_view('input/password',array('internalname'=>'pass2',
									 'validate'=>'equalTo:pass'));
$body.="</p>";

$body.='<p>Plaintext';
$body.=elgg_view('input/plaintext',array('internalname'=>'plaintext',
									 'validate'=>'maxlength:140;required'));
$body.="</p>";

$body.='<p>File (.doc,.jpg)';
$body.=elgg_view('input/file',array('internalname'=>'file',
									 'validate'=>"accept:'doc|jpg'"));
$body.="</p>";

$body.='<p>Radio';
$body.=elgg_view('input/radio',array('internalname'=>'radio',
									 'options'=>array('Yes'=>'yes','No'=>'no'),
                                     'separator'=>"&nbsp;",
                                     'validate'=>'required',
                                     'validate_messages'=>'Please select yes or no'));
$body.="</p>";

$body.='<p>Checkbox';
$body.=elgg_view('input/checkboxes',array('internalname'=>'checkboxes',
									 'options'=>array('Yes'=>'yes','No'=>'no','Maybe'=>'maybe','Perhaps'=>'perhaps'),
                                     'separator'=>"&nbsp;",
                                     'validate'=>'required;rangelength:[2,3]',
                                     'validate_messages'=>'Please select between 2 or three options'));
$body.="</p>";

/*
$body.='<p>Pulldown';
$body.=elgg_view('input/pulldown',array('internalname'=>'pulldown',
									 'options_values'=>array(''=>'','yes'=>'Yes','no'=>'No','maybe'=>'Maybe','perhaps'=>'Perhaps'),
									 'validate'=>'required'));
$body.="</p>";
*/
$body.=elgg_view('input/submit',array('value'=>'save'));

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