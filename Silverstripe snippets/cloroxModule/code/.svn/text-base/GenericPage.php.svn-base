<?php
/*
 * Class GenericPage
 * Describes the Model for a GenericPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class GenericPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Description' => 'HtmlText'
    );

    static $many_many = array("FeaturePanel" => "FeaturePanel");

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');


        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class GenericPage_Controller extends Page_Controller {

    public function init() {
        Requirements::javascript("js/GenericPage.js"); 
        parent::init();
    }

}