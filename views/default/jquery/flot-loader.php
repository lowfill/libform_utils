<?php
/**
 * Flot (http://code.google.com/p/flot/) plugins loader
 * 
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2011
 * @link http://github.com/lowfill/libform_utils
 * 
 */

if(is_array($vars["flot_modules"])){
	foreach($vars["flot_modules"] as $module){
?>

<script type="text/javascript" src="<?php echo $vars["url"];?>mod/libform_utils/vendors/flot/jquery.flot.<?php echo $module?>.js"></script>

<?php
	} 
}
?>