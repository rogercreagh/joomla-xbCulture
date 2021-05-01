<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_list/helper.php
 * @version 0.1.0 1st May 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class modXbcultureListHelper {
	
	static function getItems($params) {
		$cnt = $params->get('itemcnt');
		$comp = $params->get('comp');
//		$usebooks = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usebooks');
//		$usefilms = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usefilms');
		$filt = $params->get('filter');
		$display = $params->get('display');
		$sortby = $params->get('sortby');
		$sortdir = $params->get('sortdir');
		switch ($sortby) {
			case 1:
				$order = 'cat_date';
				break;
			case 2:
				$order = 'rating';	
				break;				
			default:
				$order = 'title';
				break;
		}
		$img = ($comp=='xbbooks') ? 'cover_img' : 'poster_img';
		$db = Factory::getDbo();
		$items = array();
		$query = $db->getQuery(true);
		$query->select('a.id AS id, a.cat_date, a.title, b.'.$img.' AS image')
		->from('#_'.$comp.' AS a');
		if ($sortby == 'rating') {
			$itemid = ($comp=='xbbooks') ? 'book_id' : 'film_id';
			$query->join('INNER','#_'.$comp.'reviews AS r ON '.$itemid.' = a.id');
		}
		$query->order($order.' '.$sortdir);
		if ($order != 'title') {
			$query->order($order.' ASC');
		}
		$db->setQuery($query,0,$cnt);
		$items = $db->loadObjectList();
		return $items;
		
//		$books = array();
// 		if ($usefilms) {
// 			if ($order == 'rev_date') {
// 				$query->join('INNER','#__xbfilmreviews AS r ON film_id = f.id');
// 				$query->select('r.rating');
// 			}
// 			$query->order($order.' DESC');
// 			$db->setQuery($query,0,$cnt);
// 			$films = $db->loadObjectList();
// 		}
// 		if ($usebooks) {
// 			$query = $db->getQuery(true);
// 			$query->select('b.id AS id, '.$order.' AS odate, b.title, b.cover_img AS image, "book" AS com')
// 			->from('#__xbbooks AS b');
// 			if ($order == 'rev_date') {
// 				$query->join('INNER','#__xbbookreviews AS r ON book_id = b.id');
// 				$query->select('r.rating');
// 			}
// 			$query->order($order.' DESC');
// 			$db->setQuery($query,0,$cnt);
// 			$books = $db->loadObjectList();
// 		}
// 		$items = array_merge($films,$books);
// 		usort($items,'modXbcultureRecentHelper::dateSort');
// 		return $items;
	}
		
	static function dateSort( $a, $b ) {
		return $a->odate == $b->odate ? 0 : (( $a->odate > $b->odate ) ? -1 : 1);
	}
}

?>
