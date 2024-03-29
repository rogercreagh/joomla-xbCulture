<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_recent/helper.php
 * @version 0.2.0 28th October 2022
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class modXbcultureRecentHelper {
	
	static function getItems($params) {
		$cnt = $params->get('itemcnt');
		$usebooks = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usebooks');
		$usefilms = Factory::getSession()->get('xbbooks_ok',false) && $params->get('usefilms');
		$db = Factory::getDbo();
		$films = array();
		$books = array();
		if ($usefilms) {
		    $order = $params->get('reviewed') ? 'rev_date' : 'last_seen';
		    $query = $db->getQuery(true);
			$query->select('f.id AS id, '.$order.' AS odate, f.title, f.poster_img AS image, "film" AS com')
			->from('#__xbfilms AS f');
			if ($order == 'rev_date') {
				$query->join('INNER','#__xbfilmreviews AS r ON film_id = f.id');
				$query->select('r.rating');
			}
			$query->order($order.' DESC');
//			$query->group('id');
			$db->setQuery($query,0,$cnt);
			$films = $db->loadObjectList();
		}
		if ($usebooks) {
		    $order = $params->get('reviewed') ? 'rev_date' : 'last_read';
		    $query = $db->getQuery(true);
			$query->select('b.id AS id, '.$order.' AS odate, b.title, b.cover_img AS image, "book" AS com')
			->from('#__xbbooks AS b');
			if ($order == 'rev_date') {
				$query->join('INNER','#__xbbookreviews AS r ON book_id = b.id');
				$query->select('r.rating');
			}
			$query->order($order.' DESC');
			$db->setQuery($query,0,$cnt);
			$books = $db->loadObjectList();
		}
		$items = array_merge($films,$books);
		usort($items,'modXbcultureRecentHelper::dateSort');
		
		// we may get unwanted duplicate ratings in the list
		// this will take the first one only in the ordered list
		if ($order == 'rev_date') {
		    $known = array();
		    $filtered = array_filter($items, function ($val) use (&$known) {
		        $unique = !in_array($val->id, $known);
		        $known[] = $val->id;
		        return $unique;
		    });
		    $items = $filtered;
		}
		
		
		return $items;
	}
		
	static function dateSort( $a, $b ) {
		return $a->odate == $b->odate ? 0 : (( $a->odate > $b->odate ) ? -1 : 1);
	}
}

?>
