<?php
class SnapGuide extends DataObject {
    static $db = array(
        'Name' => 'HTMLText',
        'Embed_Code_Wide' => 'HTMLText',
        'Embed_Code_Narrow' => 'HTMLText',
    );

    public static $summary_fields = array(
        'ID'=>'ID',
        'Name' => 'Name',
       
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Embed_Code_Wide'));
		$fields -> addFieldToTab('Root.Main', new TextAreaField('Embed_Code_Narrow'));
        return $fields;
    }
     /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }
        

}
