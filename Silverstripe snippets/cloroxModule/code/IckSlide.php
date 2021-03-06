<?php
/*
 * Class IckSlide
 *
 * Describes the Model for a IckSlide (To be added to Caroussels)
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: IckSlide.php 19771 2013-03-12 22:40:08Z lmartin $
 *
 * Relationships:
 *
 * 
 *
 */
class IckSlide extends DataObject{

	public static $db = array(
        'Title'=>'Varchar',
        'SortOrderIckSlide'=>'Int'
    );
	public static $has_one = array(
		'IcktionaryItem'=>'IcktionaryItem',
		'IckIllustrator'=>'IckIllustrator',
		'IckAuthor'=>'IckAuthor',
		'Welcome'=>'Welcome',
        'LaughPage' => 'LaughPage');


	public static $default_sort='SortOrderIckSlide';
}