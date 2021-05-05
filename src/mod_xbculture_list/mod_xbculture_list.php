<?php
/*******
 * @package xbCulture
 * @filesource mod_xbculture_list/mod_xbculture_list.php
 * @version 0.1.0 4th May 2021
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2021
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;

require_once( dirname(__FILE__).'/helper.php' );
JLoader::register('XbcultureHelper', JPATH_ADMINISTRATOR . '/components/com_xbpeople/helpers/xbculture.php');
XbcultureHelper::checkComponent('com_xbfilms');
XbcultureHelper::checkComponent('com_xbbooks');

$info = '';

$items = modXbcultureListHelper::getitems( $params );

$document	= Factory::getDocument();
$document->addStyleSheet(JURI::base(true).'/media/com_xbpeople/css/xbculture.css');
require( JModuleHelper::getLayoutPath( 'mod_xbculture_list' ));

?>
