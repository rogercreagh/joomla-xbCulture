<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_recent/tmpl/default.php
 * @version 0.1.0 1st May 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
$comp = $params->get('comp');
$view = $comp=='xbfilms' ? 'film' : 'book';
?>
<div class="xb095 xbdarkgrey xbmb8">
	<?php echo $params->get('pretext'); ?>
</div>

<ul style="list-style:none; margin-left:0;">
<?php foreach ($items as $item) {
	$link='index.php?option=com_'.$comp.'&view='.$view.'&id='.$item->id; ?>
	<li><span class="icon-<?php echo ($comp=='xbfilms'? 'screen xbfilm':'book xbbook');?>"></span>
    	<a href="<?php echo $link;?>"><?php echo $item->title;?></a>
    	<br /><span class="xbml20">&nbsp;
    	<?php if ($params->get('sortby')=='rating') : ?>
        	<span class="xbhlt xbbold"><?php echo $item->rating;?></span> <span class="icon-<?php echo ($item->rating==0 ? 'thumbs-down xbred':'star xbgold');?>"> </span>
    	<?php endif; ?>
    	<span class="xb09"><?php echo HtmlHelper::date($item->cat_date , Text::_('d M Y'));?></span></span></li>
<?php } ?>
</ul>
<div class="xb095 xbdarkgrey">
	<?php echo $params->get('posttext'); ?>
</div>
