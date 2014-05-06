<?php
/*
 * Class CausesPage
 *
 * Describes the Model for a CausesPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CausesPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Title_1' => 'Varchar',
        'Title_2' => 'Varchar',
        'Slogan' => 'Text',
    );

    static $many_many = array(
        "FeaturePanel" => "FeaturePanel",
    );

     public static $many_many_extraFields = array(
        'FeaturePanel' => array('SortOrderCausePageFeaturePanel' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');

        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title1_Class'));

        //***************** Titles
        $fields -> addFieldToTab('Root.Main', new TextField('Title_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title_2'));

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderCausePageFeaturePanel'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;
    }

     public function FeaturePanel() {
        return $this -> getManyManyComponents('FeaturePanel') -> sort('SortOrderCausePageFeaturePanel');
    }
}

class CausesPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }

}
