<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_recent/tmpl/default.php
 * @version 0.1.0 4th May 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

global $info;

$comp = $params->get('comp');
switch ($comp) {
	case 'xbfilms':
		$view = 'film';
		$perfilt = $params->get('fperfilt');
		break;
	case 'xbbooks':
		$view = 'book';
		$perfilt = $params->get('bperfilt');
		break;
	default:
		$view = '';
		$perfilt = '';
	break;
}
$link = 'index.php?option=com_'.$comp.'&view='.$view.'&id=';
?>
  
<div style="max-width:300px;">
	<div class="xb095 xbdarkgrey xbmb8">
		<?php echo $params->get('pretext'); ?>
	</div>
	
	<?php if ($params->get('display')=='tit') : ?>
		<ul style="list-style:none; margin-left:0;">
		<?php foreach ($items as $item) : ?>
			<li><span class="icon-<?php echo ($comp=='xbfilms'? 'screen xbfilm':'book xbbook');?>"></span>
		    	<a href="<?php echo $link.$item->id;?>"><?php echo $item->title;?></a>
		    	<?php if (($params->get('filter')=='person') && (!empty($perfilt)) ) {
		    		echo ' (<i>'.$item->role.'</i>)';
		    	}?>
		    	<br />
		    	<span class="xbml20">&nbsp;
			    	<?php if (($params->get('sortby')=='rat') 
			    			|| ($params->get('reviewed')==1) 
			    			|| ($params->get('filter') == 'rating')) : ?>
			        	<span class="xbhlt xbbold"><?php echo $item->rating;?></span> 
			        	<span class="icon-<?php echo ($item->rating==0 ? 'thumbs-down xbred':'star xbgold');?>"> </span>
			    	<?php endif; ?>
			    	<span class="xb09"><?php echo HtmlHelper::date($item->cat_date , Text::_('d M Y'));?></span>
		    	</span>
		    </li>
		<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<?php $cols = $params->get('cols');
		$rcnt = 0; ?>
		<div class="row-fluid xbmb8">
	    	<?php if ($cols==5) { echo '<div class="span1"></div>'; } ?>
			<?php foreach ($items as $item) : ?>
				<?php $ratstr = ''; 
					if (($params->get('sortby')=='rat') 
				    	|| ($params->get('reviewed')==1) 
						|| ($params->get('filter') == 'rating')) {
							if ($item->rating==0) {
								$ratstr = '<span class=\'icon-thumbs-down\' style=\'padding-left:20px\'></span>';
							} else {
								$ratstr = ' <span style=\'padding-left:20px\'>( '.$item->rating.'</span>';
								$ratstr .= '<span class=\'icon-star xbgold\'></span>)';
							}
						} ?>
				<?php  $src = trim($item->image); ?>
				<?php if ((!$src=='') && (file_exists(JPATH_ROOT.'/'.$src))) : ?>
			    	<?php $rcnt++; 
					$src = JUri::root().$src;
					switch ($params->get('tiptype')) {
						case 'both':
							$tip = '<img src=\''.$src.'\' style=\'width:'.$params->get('tipwid').'px;\' />';
							$tip .='<br /><b>'.$item->title.'</b>'.$ratstr;
							break;
						case 'title':
							$tip ='<b>'.$item->title.'</b><br />Rating: '.$ratstr;
							break;							
						default:
							$tip = '';
							break;
					} ?>
					<div class="span<?php echo floor((12/$cols)); ?>">
						<a href="<?php echo $link.$item->id; ?>">
							<img class="hasTooltip" 
								src="<?php echo $src; ?>"
								title="" data-original-title="<?php echo $tip; ?>"
								data-placement="<?php echo $params->get('tippos'); ?>"
								border="0" alt="" />							                          
		 				</a>
					</div>
			        <?php if ($rcnt == $cols) {
			           	echo '</div><div class="row-fluid xbmb8">';
			           	$rcnt = 0; 
			         } ?>
				<?php endif; ?>	
			<?php endforeach; ?>
	    </div>
	 <?php endif; ?>

	<div class="xb095 xbdarkgrey">
		<?php if (!empty($info)) {
			echo '<i>'.$info.'</i><br />'; 			
		}
		echo $params->get('posttext'); ?>
	</div>
</div>
