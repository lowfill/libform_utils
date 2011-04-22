<?php
/**
 * Validation framework loading
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
*/
if(!defined('VALIDATION_FRAMEWORK')){
    define('VALIDATION_FRAMEWORK','1.0');
    $current_language=get_language();
?>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/additional-methods.js" ></script>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/lib/jquery.metadata.js" ></script>
<?php if($current_language!="en"){?>
<script type="text/javascript" src="<?php echo $vars['url']?>mod/libform_utils/vendors/jquery-validate/localization/messages_<?php echo $current_language?>.js" ></script>
<?php
    }
}
?>