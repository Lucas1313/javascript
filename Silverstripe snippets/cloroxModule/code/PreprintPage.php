<?php
class PreprintPage extends Page {

    static $db = array(
        'Title' => 'Varchar',
        'Headline' => 'HtmlText',
        'Description' => 'HtmlText',
        'Coupon_Value' => 'Varchar',
        'CTA_Title' => 'HtmlText',
        'CTA_Link' => 'Text',
        'Instructions' => 'HtmlText',

    );

    static $has_one = array(
        'ProductImage' => 'Image',
        'ExtraImage' => 'Image'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');

        $dateField = new DateField('Date');

        $fields -> addFieldToTab('Root.Main', new TextField('Headline'));
        $fields -> addFieldToTab('Root.Main', $dateField, 'Description');
        $fields -> addFieldToTab('Root.Main', new TextField('Coupon_Value'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Link'));

        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Instructions'));


        return $fields;
    }

}

class PreprintPage_Controller extends Page_Controller {
}
?>