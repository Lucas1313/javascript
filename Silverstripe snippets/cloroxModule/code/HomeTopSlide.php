<?php
/*
 * Class TopItemSlide
 *
 * Describes the Model for a SlideTopItem To be added to carousels
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: HomeTopSlide.php 20094 2013-03-19 01:44:12Z lmartin $
 *
 * Relationships:
 *
 * 
 *
 */
class HomeTopSlide extends DataObject{

	public static $db = array(
        'Title'=>'Varchar',
        'SortOrder'=>'Int',
        'User_Click_Counter' =>'Int'
    );
	public static $has_one = array(
        'FeaturePanel'=>'FeaturePanel',
		'Welcome'=>'Welcome');

	public static $default_sort='SortOrder'; 
    
}