<?php
class HowToUseLocation extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Description' => 'Text'
    );
    static $has_one = array('HowToUseLocation' => 'HowToUseLocation');
    static $has_many = array('HowToUseLocationItems' => 'HowToUseLocationItem');

    public static $summary_fields = array(
        'Name' => 'Name',
        'Description' => 'Description',
        'IngredientImage' => 'IngredientImage'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        /************************* Products *********/

        $ProductsField = new GridField('HowToUseAction', 'HowToUseAction', $this -> HowToUseActions(), GridFieldConfig_RelationEditor::create());

        $fields -> addFieldToTab('Root.Main', HowToUseAction);

        return $fields;
    }

}
?>