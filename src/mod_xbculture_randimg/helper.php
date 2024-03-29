<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_randimg/helper.php
 * @version 0.2.0 28th October 2022
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class modXbcultureRandimgHelper {
	
	static function getItems($params) {
	    $bcnt = $params->get('bookcnt');
	    $fcnt = $params->get('filmcnt');
	    $usebooks = Factory::getSession()->get('xbbooks_ok',false) && ($bcnt>0);
	    $usefilms = Factory::getSession()->get('xbbooks_ok',false) && ($fcnt>0);
	    $reviewed = $params->get('reviewed');
	    
	    $db = Factory::getDbo();
	    $films = array();
	    $books = array();
	    if ($usefilms) {
	        $query = $db->getQuery(true);
	        $query->select('f.id AS id, f.title, f.poster_img AS image, "film" AS com')
	        ->from('#__xbfilms AS f');
 	        if ($reviewed) {
 	            $query->join('INNER','#__xbfilmreviews AS r ON film_id = f.id');
 	            $query->select('r.rating');
 	            $query->group('f.id');
 	        }
	        $query->where('f.poster_img <> ""');	        
	        $db->setQuery($query);
	        $films = $db->loadObjectList();
	        // if we are using ratings we may get unwanted duplicates in the list
	        // this will take the first one only in the ordered list
// 	        if ($reviewed==1) {
// 	            $known = array();
// 	            $filtered = array_filter($films, function ($val) use (&$known) {
// 	                $unique = !in_array($val->id, $known);
// 	                $known[] = $val->id;
// 	                return $unique;
// 	            });
// 	            $films = $filtered;
// 	        }
	        //if number returned more than $cnt pick random items from the list
	        if (count($films)>$fcnt) {
	            $randkeys = array_rand($films,$fcnt);
	            $randitems = array();
	            if (!is_array($randkeys)) {
	            	$randitems[]=$films[$randkeys];
	            } else {
	            	foreach ($randkeys as $k) {
	            		$randitems[]=$films[$k];
	            	}
	            }
	            $films = $randitems;
	        }
	    }
	    if ($usebooks) {
	        $query = $db->getQuery(true);
	        $query->select('b.id AS id, b.title, b.cover_img AS image, "book" AS com')
	        ->from('#__xbbooks AS b');
	        if ($reviewed) {
	            $query->join('INNER','#__xbbookreviews AS r ON book_id = b.id');
	            $query->select('r.rating');
	            $query->group('b.id');
	        }
	        $query->where('b.cover_img <> ""');
	        $db->setQuery($query);
	        $books = $db->loadObjectList();
// 	        if ($reviewed==1) {
// 	            $known = array();
// 	            $filtered = array_filter($books, function ($val) use (&$known) {
// 	                $unique = !in_array($val->id, $known);
// 	                $known[] = $val->id;
// 	                return $unique;
// 	            });
// 	                $books = $filtered;
// 	        }
	        //if number returned more than $cnt pick random items from the list
	        if (count($books)>$bcnt) {
	            $randkeys = array_rand($books,$bcnt);
	            $randitems = array();
	            if (!is_array($randkeys)) {
	            	$randitems[]=$books[$randkeys];
	            } else {
	            	foreach ($randkeys as $k) {
	            		$randitems[]=$books[$k];
	            	}
	            }
	            $books = $randitems;
	        }
	    }
	    
	    $items = array_merge($films,$books);
	    shuffle($items);	    
	    return $items;
	    
	}
		
}

?>
