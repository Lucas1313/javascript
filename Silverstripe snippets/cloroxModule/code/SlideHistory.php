<?php
/*
 * Class SlideHistory
 *
 * Describes the Model for a Slide To be added to carousels
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: SlideHistory.php 18878 2013-02-23 09:21:31Z jware $
 *
 * Relationships:
 *
 * 
 *
 */
class SlideHistory extends DataObject{

    public static $db = array(
        'Title'=>'Varchar',
        'SortOrder'=>'Int'
    );
    public static $has_one = array(
        'HistoryPanel'=>'HistoryPanel');

    public static $default_sort='SortOrder';
}