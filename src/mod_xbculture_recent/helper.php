<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_recent/helper.php
 * @version 0.1.2 1st May 2021
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
		$order = $params->get('reviewed') ? 'rev_date' : 'cat_date';
		$db = Factory::getDbo();
		$films = array();
		$books = array();
		if ($usefilms) {
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
		return $items;
	}
		
	static function dateSort( $a, $b ) {
		return $a->odate == $b->odate ? 0 : (( $a->odate > $b->odate ) ? -1 : 1);
	}
}

?>
