<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_randimg/tmpl/default.php
 * @version 0.1.0 27th April 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


?>
<ul>
<?php foreach ($items as $item) {
  $link='index.php?option=com_xb'.$item->component.'s&view='.$item->component.'&id='.$item->id;
  echo '<img src="'.JURI::root().$item->image.'" width="49%" />';
}?>
