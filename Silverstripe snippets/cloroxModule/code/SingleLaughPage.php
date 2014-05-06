<?php
/*
 * SingleLaughPage
 *
 * Describes the Model for a SingleLaughPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: SingleLaughPage.php 18615 2013-02-18 19:56:12Z lmartin $
 *
 * Relationships:
 * HasONe =
 * HasMany =
 * many-many =
 * belong-many-many =
 *
 */
class SingleLaughPage extends Page {
    static $db = array(
        'Date' => 'Date',
        'Author' => 'Text',
        'Title'=>'Text'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Author'), 'Content');

        return $fields;
    }

}

class SingleLaughPage_Controller extends Page_Controller {
}
?>