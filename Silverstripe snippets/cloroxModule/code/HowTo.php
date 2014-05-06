<?php
/*
 * Class HowTo
 *
 * Describes the Model for a HowTo 
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: HowTo.php 18424 2013-02-13 23:25:56Z lmartin $
 *
 * Relationships:
 *
 * 
 *
 */
class HowTo extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Title' => 'Text',
        'Description' => 'Text'
    );
    static $belong_many_many = array('SingleProductPage' => 'SingleProductPage');
}
