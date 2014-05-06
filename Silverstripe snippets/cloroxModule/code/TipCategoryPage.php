<?php
class TipCategoryPage extends Page {
    static $allowed_children = array('SingleTipPage');
    static $db = array(
        'Title'=>'Text',
        'IconSource' => 'Text',
        'CategoryName' => 'Text'
    );

    public static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('IconSource'));
        $fields -> addFieldToTab('Root.Main', new TextField('CategoryName'));
        return $fields;
    }

}

class TipCategoryPage_Controller extends Page_Controller {
}
?>