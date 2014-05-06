<?php
/*
 * Class Slide
 *
 * Describes the Model for a Slide To be added to carousels
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: SlideTopItem.php 18878 2013-02-23 09:21:31Z jware $
 *
 * Relationships:
 *
 * 
 *
 */
class SlideTopItem extends DataObject{

	public static $db = array(
        'Title'=>'Varchar',
        'SortOrder'=>'Int',
        'Twitter_Link'=>'Text',
        'Twitter_Text'=>'Text'
    );
	public static $has_one = array(
		'FeaturePanel'=>'FeaturePanel',
		'Welcome'=>'Welcome');

	public static $default_sort='SortOrder'; 
}