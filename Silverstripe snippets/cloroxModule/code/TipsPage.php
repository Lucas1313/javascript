<?php
class TipsPage extends Page {
    static $allowed_children = array('TipCategoryPage');
    static $db = array(
        'Date' => 'Date',
        'Author' => 'Text',
        'Title'=>'Varchar',
    );

    public static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);
        $fields -> addFieldToTab('Root.Main', new TextField('Author'));

        return $fields;
    }

}

class TipsPage_Controller extends Page_Controller {
}
?>