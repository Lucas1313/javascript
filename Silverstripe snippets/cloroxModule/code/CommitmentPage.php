<?php
/*
 * Class CommitmentPage
 *
 * Describes the Model for a CommitmentPage 
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CommitmentPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Title_1' => 'Varchar',
        'Title_2' => 'Varchar',
        'Slogan' => 'Text',
    );

    static $has_many = array("FeaturePanel" => "FeaturePanel");

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
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class CommitmentPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }

}
