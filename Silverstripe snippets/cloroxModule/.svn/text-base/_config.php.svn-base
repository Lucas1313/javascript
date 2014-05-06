<?php

global $project;
$project = 'cloroxModule';

MySQLDatabase::set_connection_charset('utf8');

// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

DataObjectAsPage::enable_versioning();

// enable full search
FulltextSearchable::enable();

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('clorox');

// Sets blog entries to allow commenting by default.
BlogEntry::$defaults["ProvideComments"] = true;

// Set the site locale
i18n::set_locale('en_US');

// Enable nested URLs for this site (e.g. page/sub-page/)
if (class_exists('SiteTree')) SiteTree::enable_nested_urls();

define('CloroxAjaxDir','cloroxModule');
Object::add_extension('DataObject', 'StringManipulator_Controller');
Object::add_extension('DataObject', 'Relationship_Controller');
Object::add_extension('DataObject', 'AlsoLikeItem_Controller');
Object::add_extension('DataObject', 'CloroxApi_Controller');
Object::add_extension('DataObject', 'CssClasses_Controller');
Object::add_extension('Member', 'SyncedMemberRole');
Object::add_extension('SiteTree', "FilesystemPublisher('cache/', 'html')");
//Object::add_extension('DataObject', 'IckMarketingCampaign_Controller');
Object::add_extension('Page','CustomProductMenu_Controller');
// set up the Clorox specific password hashing algorithm
Config::inst()->update('PasswordEncryptor', 'encryptors',
					   array('ccl' => array('CloroxEncryptor'
											=> null)));

// set up the default sender for SS email
Email::setAdminEmail('clorox-noreply@clorox.com');

// force ssl for the following pages. this will change from http:// to https:// protocol
/*Director::forceSSL(); */

//  This is how you can force specific URLs to be https instead of http
if (Director::isLive()) {
	Director::forceSSL(array(
		'/^sign-up/',
		'/^sign-in/',
		'/^contact-us/'
	));
}

/*/  Memcache config
if(defined('MEMCACHE_ENABLED') && class_exists('CCL_Memcache')) CCL_Memcache::Set_Config(MEMCACHE_ENABLED);
 * 
 */
DataObject::$create_table_options['MySQLDatabase'] = 'ENGINE=MyISAM';
