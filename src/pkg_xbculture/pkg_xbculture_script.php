<?php
/**
 * @package xbCulture-Package
 * @filesource pkg_xbculture_script.php
 * @version 0.1.1 3rd November 2022
 * @desc install, upgrade and uninstall actions
 * @author Roger C-O
 * @copyright (C) Roger Creagh-Osborne, 2022
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
// No direct access to this file
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;

class pkg_xbcultureInstallerScript
{
    protected $jminver = '3.9';
    protected $jmaxver = '4.0';
    
    function preflight($type, $parent)
    {
        $jversion = new Version();
        $jverthis = $jversion->getShortVersion();
        if ((version_compare($jverthis, $this->jminver,'lt')) || (version_compare($jverthis, $this->jmaxver, 'ge'))) {
            throw new RuntimeException('xbBooks requires Joomla version greater than or equal to '.$this->jminver. ' and less than '.$this->jmaxver.'. You have '.$jverthis);
        }
     }
    
    function install($parent)
    {
    }
    
    function uninstall($parent) {
        $db = Factory::getDBO();
//      	$db->setQuery('SELECT enabled FROM #__extensions WHERE element = '.$db->quote('com_xbfilms'));
//      	$res = $db->loadResult();    	
//      	if ($res) {
//      	    $message = 'xbFilms is still installed. If you wish to uninstall xbBooks then just uninstall the component for now.';
//      	    //books component could have been uninstalled manually so we'll redirect to xbfilms dashboard as we know that exists
//      	    $targ = Uri::base().'index.php?option=com_xbfilms&view=cpanel&err='.urlencode($message);
//    	        //ndex.php?option=com_installer&view=manage&filter_search=xb //but this would miss the message
//      	    header("Location: ".$targ);
//      	    exit();
//      	}
        $oldval = Factory::getSession()->set('xbpkg', 'culture');

     	echo '<div style="padding: 7px; margin: 0 0 8px; list-style: none; -webkit-border-radius: 4px; -moz-border-radius: 4px;
	border-radius: 4px; background-image: linear-gradient(#ffffff,#efefef); border: solid 1px #ccc;">';
    	echo '<h4>xbCulture Package Uninstalled</h4>';
    	$db->setQuery('SELECT enabled FROM #__extensions WHERE element = '.$db->quote('com_xbbooks'));
    	if ($db->loadResult()) {
    	    echo '<p>xbBooks component uninstalled.</p>';
    	} else {
    	    echo '<p>xbBooks component appears to have already been uninstalled.</p>';
    	}
    	$db->setQuery('SELECT enabled FROM #__extensions WHERE element = '.$db->quote('com_xbfilms'));
    	if ($db->loadResult()) {
    	    echo '<p>xbFilms component uninstalled.</p>';
    	} else {
    	    echo '<p>xbFilms component appears to have already been uninstalled.</p>';
    	}
    	$db->setQuery('SELECT enabled FROM #__extensions WHERE element = '.$db->quote('com_xbpeople'));
    	if ($db->loadResult()) {
    	    echo '<p>xbPeople component uninstalled.</p>';
    	} else {
    	    echo '<p>xbPeople component appears to have already been uninstalled.</p>';
    	}
    	echo '<p>xbCulture modules List, RandImg, and Recent uninstalled.</p>';
    	echo '</div>';
    }
    
    function update($parent)
    {
    	echo '<div style="padding: 7px; margin: 0 0 8px; list-style: none; -webkit-border-radius: 4px; -moz-border-radius: 4px;
	border-radius: 4px; background-image: linear-gradient(#ffffff,#efefef); border: solid 1px #ccc;">';
    	echo '<h3>xbCulture Package updated to version ' . $parent->get('manifest')->version.' '.$parent->get('manifest')->creationDate . ' with extensions</h3>';
    	echo '<ul><li>xbBooks v.' . $parent->get('manifest')->xbbooks_version . '</li>';
    	echo '<li>xbFilms v.' . $parent->get('manifest')->xbfilms_version . '</li>';
    	echo '<li>xbPeople v.' . $parent->get('manifest')->xbpeople_version . '</li>';
    	echo '<li>xbCulture Modules List v.' . $parent->get('manifest')->xbculture_list_version.', ';
        echo 'RandImg v.' . $parent->get('manifest')->xbculture_randimg_version.', ';
        echo 'Recent v.' . $parent->get('manifest')->xbculture_recent_version.'</li>';
    	echo '</ul>';
    	echo '<p>For details see <a href="https://crosborne.co.uk/xbculture/changelog" target="_blank">
            www.crosborne.co.uk/xbculture/changelog</a></p>';
    	echo '</div>';
    }
    
    function postflight($type, $parent)
    {
    	if ($type=='install') {
	    	echo '<div style="padding: 7px; margin: 0 0 8px; list-style: none; -webkit-border-radius: 4px; -moz-border-radius: 4px;
		border-radius: 4px; background-image: linear-gradient(#ffffff,#efefef); border: solid 1px #ccc;">';
	    	echo '<h3>xbCulture Package installed</h3>';
	    	echo '<p>Package version <b>'.$parent->get('manifest')->version.' '.$parent->get('manifest')->creationDate.'</b><br />';
	    	echo 'Extensions included: </p>';
	    	echo '<ul><li><b>xbBooks '.$parent->get('manifest')->xbbooks_version.'</b>: manage/display books details and reviews</li>';
	    	echo '<li><b>xbFilms v.' . $parent->get('manifest')->xbfilms_version . '</b>: manage/display films details and reviews</li>';
	    	echo '<li><b>xbPeople v.' . $parent->get('manifest')->xbpeople_version . '</b>: manage/display people &amp; characters</li>';
	    	echo '<li><b>Module List v.' . $parent->get('manifest')->xbculture_list_version.'</b>: display a (partial) list of books/films</li>';
	    	echo '<li><b>Module RandImg v.' . $parent->get('manifest')->xbculture_randimg_version.'</b>: display a random book cover or film poster</li>';
	    	echo '<li><b>Module Recent v.' . $parent->get('manifest')->xbculture_recent_version.'</b>: display a list of recent entries</li>';
	    	echo '</ul>';
	    	echo '<p>For help and information see <a href="https://crosborne.co.uk/xbculture/doc" target="_blank">
	            www.crosborne.co.uk/xbculture/doc</a></p>';
	    	echo '<h4>Next steps</h4>';
	    	echo '<p><b>Important</b> Before starting review &amp; set &amp; save each components options.&nbsp;&nbsp;';
	    	echo 'Default values are provided but you need to save options to ensure they are picked up.';
	    	echo '</p>';
	    	echo '<p><b>Dashboard</b> <i>Each component has a Dashboard view which provides an overview of the component status</i>&nbsp;&nbsp;: ';
	    	echo '</p>';
	    	echo '<p><b>Sample Data</b> <i>You can install some sample data for books and films which will include some people &amp; characters</i>&nbsp;&nbsp;: ';
	    	echo 'first check the option to show sample data button on the relevant component Options Admin tab, ';
	    	echo 'and then an [Install/Remove Sample Data] button will show in the Dashboard toolbar.';
	    	echo '</p>';
	    	echo '<p>Enable and position the modules as required. Each instance of a module has its own settings';
	    	echo '</p>';
	    	echo '</div>';
	    	
	    	$message = $parent->get('manifest')->name .' v.'.$parent->get('manifest')->version.' '.$parent->get('manifest')->creationDate.' has been installed';
	    	
	    	Factory::getApplication()->enqueueMessage($message, 'message');
    	}
    }
    
}
