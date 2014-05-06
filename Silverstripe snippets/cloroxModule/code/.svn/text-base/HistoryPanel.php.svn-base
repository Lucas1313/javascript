<?php
/*
 * Class HistoryPanel
 *
 * Describes the Model for a HistoryPanel
 * Holds a set of years for the history
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id:
 *
 * Relationships:
 *
 *
 *
 */
class HistoryPanel extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Title' => 'HtmlText',
        'Subtitle' => 'HtmlText',
        'Description' => 'HtmlText'
    );
    static $has_many = array('HistoryYearItem' => 'HistoryYearItem');
    static $has_one = array('OurHistoryPage' => 'OurHistoryPage');
    public static $summary_fields = array(
        'Name' => 'Name',
        'Title' => 'Title',
        'Subtitle' => 'Subtitle',
        'Description' => 'Description'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

         $fields -> removeFieldsFromTab('Root', array(
            'HistoryYearItem',
            'Tags'
        ));

        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Description'));
        $HistoryYearItemField = new GridField('HistoryYearItem', 'HistoryYearItem', $this -> HistoryYearItem(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $HistoryYearItemField);

        return $fields;
    }

}
?>