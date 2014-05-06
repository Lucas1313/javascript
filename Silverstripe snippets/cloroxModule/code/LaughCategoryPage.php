<?php
class LaughCategoryPage extends Page {
    static $allowed_children = array('SingleIckPage');
    static $db = array(
        'IconSource' => 'Text',
        'CategoryName' => 'Text'
    );
    static $many_many = array('AlsoLikeItems' => 'AlsoLikeItem');
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('IconSource'), 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('CategoryName'), 'Content');
        return $fields;
    }

}

class LaughCategoryPage_Controller extends Page_Controller {
}
?>