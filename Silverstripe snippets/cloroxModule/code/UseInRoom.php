<?php
/*
 * UseInRoom extends DataObject
 *
 * Describes the Model for a UseInRoom Object
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: UseInRoom.php 29959 2014-03-25 00:00:18Z ksmith $
 *
 * Relationships:
 * HasOne = UseFor
 * HasMany = UsesOn
 * many-many =
 * belong-many-many =
 *
 */
class UseInRoom extends DataObject {

    static $db = array(
        'Name' => 'Text',
        'Code_Name' => 'Text',
        'Display_Name' => 'Text',
        'ProductName' => 'Text',
        'For' => 'Text'
    );

    static $many_many = array(
        'UseFor' => 'UseFor',
        'UsesOn' => 'UseOn'
    );

    public static $summary_fields = array(
        'ID' => 'ID',
        'Name' => 'Name',
        'Display_Name' => 'Display_Name',
        'ProductName' => 'ProductName'
    );

    public static $many_many_extraFields = array('UsesOn' => array('SortOrderUseOn' => 'Int'));

    public function UsesOn() {
        return $this -> getManyManyComponents('UsesOn') -> sort('SortOrderUseOn');
    }

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> removeFieldsFromTab('Root', array('UsesOn'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
        $fields -> addFieldToTab('Root.Main', new LiteralField('Code_Name', '<div id="Code_Name" class="field"><label class="left" for="middleColumn">Code Name</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> Code_Name . '</div></div></div>'));

        $UsesOnField = new GridField('UsesOn', 'UsesOn', $this->UsesOn(), GridFieldConfig_RelationEditor::create(50)-> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $UsesOnField);

        return $fields;
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        $strManipulator = new StringManipulator_Controller();
        if (empty($this -> Display_Name)) {
            $this -> Display_Name = $this -> Name;
        }
        if (empty($this -> Title)) {
            $this -> Title = $this -> Display_Name;
        }
        $this -> Code_Name = $strManipulator -> generateCodeName($this -> Name);

        parent::onBeforeWrite();
    }

    public function Code_Name() {
        $strManipulator = new StringManipulator_Controller();
        $Code_Name = $strManipulator -> generateCodeName($this -> Code_Name);
        if ($this -> Code_Name != $Code_Name) {
            $this -> Code_Name = $Code_Name;
            $this -> write();
        }
        return $this -> Code_Name;
    }

}
