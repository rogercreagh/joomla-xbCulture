<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_randimg/tmpl/default.php
 * @version 0.2.0 28th October 2022
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


?>

<div>
	<div class="xb095 xbdarkgrey xbmb8">
		<?php echo $params->get('pretext'); ?>
	</div>
	
	<?php $cols = $params->get('cols');
	$rcnt = 0; ?>
	<div class="row-fluid xbmb8">
		<?php foreach ($items as $item) : ?>
			<?php $ratstr = ''; 
			$link='index.php?option=com_xb'.$item->com.'s&view='.$item->com.'&id='.$item->id; 
			if ($params->get('reviewed')==1) {
						if ($item->rating==0) {
							$ratstr = '<span class=\'icon-thumbs-down\' style=\'padding-left:20px\'></span>';
						} else {
//							$ratstr = ' <span style=\'padding-left:20px\'>( '.$item->rating.'</span>';
							$ratstr .= str_repeat('<span class=\'icon-star xbgold\'></span>)',$item->rating);
						}
					} ?>
			<?php  $src = trim($item->image); ?>
			<?php if ((!$src=='') && (file_exists(JPATH_ROOT.'/'.$src))) : ?>
		    	<?php $rcnt++; 
				$src = JUri::root().$src;
				switch ($params->get('tiptype')) {
					case 'both':
						$tip = '<img src=\''.$src.'\' style=\'width:'.$params->get('tipwid').'px;\' />';
						$tip .='<br /><b>'.$item->title.'</b><br />'.$ratstr;
						break;
					case 'title':
						$tip ='<b>'.$item->title.'</b>';
						$tip .= ($ratstr=='') ? '' : '<br />Rating: '.$ratstr;
						break;							
					default:
						$tip = '';
						break;
				} ?>
				<div class="span<?php echo floor((12/$cols)); ?>">
					<a href="<?php echo $link; ?>">
						<img src="<?php echo $src; ?>"
							<?php if ($tip) : ?>
								class="hasTooltip xbtip"
								title="" data-original-title="<?php echo $tip; ?>"
								data-placement="<?php echo $params->get('tippos'); ?>"
							<?php endif; ?>
							border="0" alt="" 
						/>							                          
	 				</a>
				</div>
		        <?php if ($rcnt == $cols) {
		           	echo '</div><div class="row-fluid xbmb8">';
		           	$rcnt = 0; 
		         } ?>
			<?php endif; ?>	
		<?php endforeach; ?>
	</div>
		
	<div class="xb095 xbdarkgrey">
		<?php echo $params->get('posttext'); ?>
	</div>
</div>
	