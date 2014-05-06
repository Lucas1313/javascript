<?php
/*
 * Class RoundThreeColPage
 * Describes the Model for a RoundThreeCol page.
 * This should be used for all page types that 
 * only use this one panel.
 *
 * @author Jason Ware jason.ware -at- clorox.com
 * @version $Id: RoundThreeColPage.php 20197 2013-03-20 07:48:05Z jware $
 */
class RoundThreeColPage extends Page {
    
    // fields in the database
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Title_1' => 'HtmlText',
        'Description' => 'HtmlText'
    );
    
    // this can have a number of the following 
    // associated with it, and they can be 
    // associated with other panels
    static $many_many = array(
        "FeaturePanel" => "FeaturePanel"
    );
    
    /**
     * getCMSFields function
     * used to hide and show fields in the 
     * admin page for content editing
     * 
     * @return Object $fields
     */
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextField('Title'));
        //$fields->removeFieldFromTab('Root', 'Content');
        $dateField = new DateField('Publication Date');

        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields->addFieldToTab('Root.Main', $cssClassController->titleClasses('Title1_Class'));

        //***************** Titles
        $fields->addFieldToTab('Root.Main', new TextField('Title_1'));

        //***************** Description
        $fields->addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        $FeaturePanelField = new GridField(
                                    'FeaturePanel', 
                                    'FeaturePanel', 
                                    $this->FeaturePanel(), 
                                    GridFieldConfig_RelationEditor::create()->addComponents(
                                        new GridFieldDeleteAction('unlinkrelation')
                                    )
                                 );
        $fields->addFieldToTab('Root.Main', $FeaturePanelField);
        
        
        return $fields;
    }
}

// the basic controller instantiation
class RoundThreeColPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }

}
