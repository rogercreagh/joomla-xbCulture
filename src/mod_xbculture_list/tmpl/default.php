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
$link = 'index.php?option=com_'.$comp.'&view='.$view.'&id=';
?>
<div class="xb095 xbdarkgrey xbmb8">
	<?php echo $params->get('pretext'); ?>
</div>

<?php if ($params->get('display')=='title') : ?>
	<ul style="list-style:none; margin-left:0;">
	<?php foreach ($items as $item) : ?>
		<li><span class="icon-<?php echo ($comp=='xbfilms'? 'screen xbfilm':'book xbbook');?>"></span>
	    	<a href="<?php echo $link.$item->id;?>"><?php echo $item->title;?></a>
	    	<br /><span class="xbml20">&nbsp;
	    	<?php if (($params->get('sortby')=='rat') || ($params->get('reviewed')==1) || ($params->get('filter') == 'rating')) : ?>
	        	<span class="xbhlt xbbold"><?php echo $item->rating;?></span> <span class="icon-<?php echo ($item->rating==0 ? 'thumbs-down xbred':'star xbgold');?>"> </span>
	    	<?php endif; ?>
	    	<span class="xb09"><?php echo HtmlHelper::date($item->cat_date , Text::_('d M Y'));?></span></span></li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<?php foreach ($items as $item) : ?>
		<a href="<?php echo $link.$item->id; ?>">
			<?php  $src = trim($item->image);
				if ((!$src=='') && (file_exists(JPATH_ROOT.'/'.$src))) :
					$src = Uri::root().$src;
					$tip = '<img src=\''.$src.'\' style=\'width:300px;\' />'.$item->title;
					?>
					<img class="img-polaroid hasTooltip xbimgthumb" title="" 
						data-original-title="<?php echo $tip; ?>"
						src="<?php echo $src; ?>"
								border="0" alt="" />							                          
                    	<?php  endif; ?>	                    
		</a>
	<?php endforeach; ?>
<?php endif; ?>
<div class="xb095 xbdarkgrey">
	<?php echo $params->get('posttext'); ?>
</div>
