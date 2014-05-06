<?php
/*
 * FaqCategory
 *
 * Describes the Model for a FaqCategory
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: FaqCategory.php 30151 2014-03-29 00:45:16Z jwu $
 *
 * Relationships:
 *
 * hasOne =
 * many-many = FAQs
 * belong-many-many = Products
 */
class FaqCategory extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Display_Name' => 'Text',
        'Description' => 'Text'
    );
    static $has_one = array('OurHistoryPage' => 'OurHistoryPage');
    static $belong_many_many = array('Products' => 'Product','FAQPage'=>'FAQPage');

    static $many_many = array('Faqs' => 'Faq');

    static $searchable_fields = array(
        'Name',
        'Display_Name',
        'Description'
    );
    public static $summary_fields = array(
        'Name' => 'Name',
        'Display_Name' => 'Category',
        'Description' => 'Description',
        'Link' => 'Link'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Description'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link'));
        $fields -> addFieldToTab('Root.Main', new TextField('LinkUrl'));

        //************************* Faq

        $FaqField = new GridField('Faqs', 'Faqs', $this -> Faqs(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FaqField);


        return $fields;
    }
    
    public function getTotalFaqs() {
        return $this -> Faqs() -> Count();
    }
}
