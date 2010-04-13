<?php
/**
*
* @author Diego Andrés Ramírez Aragón
* @copyright Corporación Somos más - 2008
*/
if(!defined('VALIDATION_FRAMEWORK')){
    define('VALIDATION_FRAMEWORK','1.0');
    $current_language=get_language();
?>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/lib/jquery.metadata.js" ></script>
<?php if($current_language!="en"){?>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/localization/messages_<?php echo $current_language?>.js" ></script>
<?php
    }
}
?>