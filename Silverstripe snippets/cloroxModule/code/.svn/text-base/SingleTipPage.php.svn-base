<?php
class SingleTipPage extends Page {
    static $db = array(
        'Date' => 'Date',
        'Author' => 'Text',
        'Title'=>'Text'
    );

    public static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Author'), 'Content');

        return $fields;
    }

}

class SingleTipPage_Controller extends Page_Controller {
}
?>