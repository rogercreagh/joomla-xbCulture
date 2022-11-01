<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_recent/tmpl/default.php
 * @version 0.2.0 28th October 2022
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
$sortby = $params->get('sortby','tit');
$showdate = $params->get('showdate',0);
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
		<?php echo $params->get('pretext'); 
// 		if ($params->get('showcnt')=='1'){
// 		    echo '<span class="xb09 xbit">Showing '.count($items).' of '.
// 		}
		?>
		
	</div>
	
	<?php if ($params->get('display')=='tit') : ?>
		<ul style="list-style:none; margin-left:0;">
		<?php foreach ($items as $item) : ?>
			<li><span class="icon-<?php echo ($comp=='xbfilms'? 'screen xbfilm':'book xbbook');?>"></span>
		    	<a href="<?php echo $link.$item->id;?>"><?php echo $item->title;?></a>
		    	<?php if (($params->get('filter')=='person') && (!empty($perfilt)) ) {
		    		echo ' (<i>'.$item->role.'</i>)';
		    	}?>
			    	<?php //if sorting orfiltering by review info then show rating and rev_date
			    	    if (($params->get('sortby')=='rat') 
			    			|| ($params->get('reviewed')==1) 
			    			|| ($params->get('filter') == 'rating')) : ?>
    		    	<br />
    		    	<span>&nbsp;
			        	<?php if ($item->rating==0) {
			        		echo '<span class="icon-thumbs-down xbred"></span>';
			        	} else {
    			        	echo str_repeat('<span class="icon-star xbgold"></span>', $item->rating);
    			        	echo '<br />'.HtmlHelper::date($item->rev_date , Text::_('d M Y'));
			        	} ?>
			        </span>
			        <?php  else : ?>
    			    	<?php if ($showdate) :?>
    			    		<span class="xb09">
        			    		<?php switch ($sortby) {
    			    		        case 'fdate':
    			    		            $ddate = HtmlHelper::date($item->firstdate , Text::_('d M Y'));
    			    		            break;
    			    		        case 'ldate':
    			    		            $ddate = HtmlHelper::date($item->lastdate , Text::_('d M Y'));
    			    		            break;
    			    		        default:
    			    		            $ddate = $item->year;
    			    		            break;
        			    		}			    		    
            			        echo $ddate; ?>
        			        </span> 
    			    	<?php endif; ?>
			    	<?php endif; ?>
		    </li>
		<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<?php 
		$cols = $params->get('cols');
		$rcnt = 0; ?>
		<div class="row-fluid xbmb8">
	    	<?php //if ($cols==5) { echo '<div class="span1"></div>'; } ?>
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
							$ratstr = '<br />Rating: '.$ratstr;
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
							$tip ='<b>'.$item->title.'</b>'.$ratstr;
							break;							
						default:
							$tip = '';
							break;
					} ?>
					<div class="span<?php echo floor((12/$cols)); ?>">
						<a href="<?php echo $link.$item->id; ?>">
							<img src="<?php echo $src; ?>"
								<?php if ($tip) : ?>
    								class="hasTooltip"
    								title="" data-original-title="<?php echo $tip; ?>"
    								data-placement="<?php echo $params->get('tippos'); ?>"
								<?php endif; ?>
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
