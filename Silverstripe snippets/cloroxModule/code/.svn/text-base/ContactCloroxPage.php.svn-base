<?php
class ContactCloroxPage extends Page {

    static $db = array(
        'Title' => 'Varchar',
        'Date' => 'Date',
        'Author' => 'Text'
    );

    static $has_many = array();

    static $many_many = array(
        'AlsoLikeItems' => 'AlsoLikeItem',
        'FeaturePanel' => 'FeaturePanel'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Author'), 'Content');
        $FeaturePanelField = new GridField('Panel', 'Panel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;
    }

}

class ContactCloroxPage_Controller extends Page_Controller {
    public function init() {
        parent::init();
    }

}
?>