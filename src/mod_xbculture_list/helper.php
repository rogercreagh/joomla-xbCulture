<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_list/helper.php
 * @version 0.1.0 5th May 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

class modXbcultureListHelper {
	
	static function getItems($params) {
		global $info;
		$info='';
		$cnt = $params->get('itemcnt');
		$comp = $params->get('comp');
//		$usebooks = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usebooks');
//		$usefilms = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usefilms');
		$fiction = $params->get('fiction');
		$filt = $params->get('filter');
		$display = $params->get('display');
		$sortby = $params->get('sortby');
		$sortdir = $params->get('sortdir');
		$reviewed = $params->get('reviewed');
		$filter = $params->get('filter');
		switch ($sortby) {
			case 'dat':
				$order = (($reviewed==1) ||($filter=='rating')) ? 'rev_date' : 'cat_date';
				break;
			case 'rat':
				$order = 'r.rating';	
				break;				
			default:
				$order = 'a.title';
				break;
		}
		switch ($comp) {
			case 'xbbooks':
				$ttype = 'com_xbbooks.book';
				$tlbl = 'books';
				$img = 'cover_img';
				$itemid = 'book_id';
				$tablea = '#__xbbooks';
				$rtable = '#__xbbookreviews';
				$ptable = '#__xbbookperson';
				$catfilt = $params->get('bcatfilt');
				$pfilt = $params->get('bperfilt');
				$prole = $params->get('brole');
				break;
			case 'xbfilms':
				$ttype = 'com_xbfilms.film';
				$tlbl = 'films';
				$img = 'poster_img';
				$itemid = 'film_id';
				$tablea = '#__xbfilms';
				$rtable = '#__xbfilmreviews';
				$ptable = '#__xbfilmperson';
				$catfilt = $params->get('fcatfilt');
				$pfilt = $params->get('fperfilt');
				$prole = $params->get('frole');
				break;				
			default:
				
			break;
		}
		
		$db = Factory::getDbo();
		$items = array();
		$query = $db->getQuery(true);
		$query->select('a.id AS id, a.cat_date, a.title, a.'.$img.' AS image')
		->from($tablea.' AS a');
		if (($sortby == 'rat') || ($reviewed==1) || ($filter == 'rating')){
			$query->select('r.rev_date, r.rating');
			$query->join('INNER',$rtable.' AS r ON '.$itemid.' = a.id');
		}
		if (($comp=='xbbooks') && ($fiction !='')) {
			$query->where('a.fiction = '.$db->quote($fiction));
		}
		switch ($filter) {
			case 'cat':
				$query->where('a.catid = '.$db->quote($catfilt));
				break;
			case 'tag':
				$tagfilt = $params->get('tagfilt');
				$taglogic = $params->get('taglogic');
				if (!empty($tagfilt)) {
					$tagfilt = ArrayHelper::toInteger($tagfilt);
					
					if ($taglogic==2) { //exclude anything with a listed tag
						// subquery to get a virtual table of item ids to exclude
						$subQuery = '(SELECT content_item_id FROM #__contentitem_tag_map
					WHERE type_alias = '.$db->quote($ttype).
					' AND tag_id IN ('.implode(',',$tagfilt).'))';
						$query->where('a.id NOT IN '.$subQuery);
					} else {
						if (count($tagfilt)==1)	{ //simple version for only one tag
							$query->join( 'INNER', $db->quoteName('#__contentitem_tag_map', 'tagmap')
									. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id') )
									->where(array( $db->quoteName('tagmap.tag_id') . ' = ' . $tagfilt[0],
											$db->quoteName('tagmap.type_alias') . ' = ' . $db->quote($ttype) )
											);
						} else { //more than one tag
							if ($taglogic == 1) { // match ALL listed tags
								// iterate through the list adding a match condition for each
								for ($i = 0; $i < count($tagfilt); $i++) {
									$mapname = 'tagmap'.$i;
									$query->join( 'INNER', $db->quoteName('#__contentitem_tag_map', $mapname).
											' ON ' . $db->quoteName($mapname.'.content_item_id') . ' = ' . $db->quoteName('a.id'));
									$query->where( array(
											$db->quoteName($mapname.'.tag_id') . ' = ' . $tagfilt[$i],
											$db->quoteName($mapname.'.type_alias') . ' = ' . $db->quote($ttype))
											);
								}
							} else { // match ANY listed tag
								// make a subquery to get a virtual table to join on
								$subQuery = $db->getQuery(true)
								->select('DISTINCT ' . $db->quoteName('content_item_id'))
								->from($db->quoteName('#__contentitem_tag_map'))
								->where( array(
										$db->quoteName('tag_id') . ' IN (' . implode(',', $tagfilt) . ')',
										$db->quoteName('type_alias') . ' = ' . $db->quote($ttype))
										);
								$query->join(
										'INNER',
										'(' . $subQuery . ') AS ' . $db->quoteName('tagmap')
										. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
										);
								
							} //endif all/any
						} //endif one/many tag
					}
				} //if not empty tagfilt
				break;
			case 'rating':
				$query->where('r.rating = '.$db->quote($params->get('ratfilt')));
				break;
			case 'person':
				$query->join('LEFT',$db->quoteName($ptable, 'p') . ' ON ' .$db->quoteName('a.id') . ' = ' . $db->quoteName('p.'.$itemid)); 
				$query->select('p.role');
				if (is_numeric($pfilt)) {
					$query->where('p.person_id = '.$db->quote($pfilt));
				}
				if ($prole != '') {
					$query->where('p.role = '.$db->quote($prole));
				}
				break;
			
			default:
				
				break;
		}		
		
		$query->order($order.' '.$sortdir);
		if ($order != 'title') {
			$query->order($order.' ASC');
		}
		if ($sortby=='dat') {
			$db->setQuery($query,0,$cnt);
		} else {
			$db->setQuery($query); //e may get more than we want so we'll randomly pick some after 
		}
			
		$items = $db->loadObjectList();
		
		//if number returned more than $cnt pick random items from the list
		if (count($items)>$cnt) {
			$info .= $cnt.' random '.$tlbl.' from '.count($items).' found';
			$randkeys = array_rand($items,$cnt);
			$randitems = array();
			foreach ($randkeys as $k) {
				$randitems[]=$items[$k];
			}
			$items = $randitems;
		}
		return $items;		
	}
		
	static function dateSort( $a, $b ) {
		return $a->odate == $b->odate ? 0 : (( $a->odate > $b->odate ) ? -1 : 1);
	}
			
}

?>
