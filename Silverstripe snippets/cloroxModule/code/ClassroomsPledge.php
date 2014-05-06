<?php
class ClassroomsPledge extends DataObject {
    static $db = array(
        'memberID' => 'HTMLText',
    );

    static $many_many = array('ClassroomsPledgePage' => 'ClassroomsPledgePage');

    static $belong_many_many = array();
    
    public static $summary_fields = array(
        'ID'=>'ID'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
       
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
