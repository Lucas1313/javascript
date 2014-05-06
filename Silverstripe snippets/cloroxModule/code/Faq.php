<?php
class Faq extends DataObject {
    static $db = array(
        'Title'=>'Text',
        'Name' => 'HTMLText',
        'Display_Name' => 'HTMLText',
        'Slogan' => 'HTMLText',
        'Description' => 'HTMLText',
        'Link_Title' => 'Text',
        'Link_Url' => 'Text',
        
        'Faq_Category_Relationship'=>'Varchar'
    );

    static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    static $belong_many_many = array('FaqCategory' => 'FaqCategory');
    
    public static $summary_fields = array(
        'ID'=>'ID',
        'Name' => 'Name',
        'Display_Name'=>'Display_Name',
        'Slogan' => 'Slogan',
        'Description' => 'Description',
        'Link_Title'  => 'Link_Title',
        'Link_Title' => 'Link_Title',
        'Faq_Category_Relationship'=> 'Faq_Category_Relationship'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
        
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Description'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Url'));
        $fields -> addFieldToTab('Root.Main', new TextField('Faq_Category_Relationship'));
        return $fields;
    }
     /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        
        $relationshipImportController = new Relationship_Controller();
        
       // $this -> All_Tags_General = $relationshipImportController -> updateRelationshipField($this, 'Faq_Category_Relationship', $this->FaqCategory(), 'Name');
        parent::onBeforeWrite();
    }
        

}
