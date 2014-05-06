<?php
/*
 * Class HowToUse
 *
 * Describes the Model for a HowToUse 
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: HowToUse.php 18424 2013-02-13 23:25:56Z lmartin $
 *
 * Relationships:
 *
 * 
 *
 */
class HowToUse extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Description' => 'Text'
    );

    static $has_many = array('HowToUseActions' => 'HowToUseAction');

    public static $summary_fields = array('Name' => 'Name', );

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