<?php
/*
 * Class GamesPage
 * Describes the Model for a GamesPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class GamesPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        // 'Title' => 'HTMLText',
        'Title_1' => 'HTMLText',
        'Description' => 'HTMLText'
    );

     /*1. set relationship*/
    static $many_many = array(
        "FeaturePanel" => "FeaturePanel");

    public static $many_many_extraFields=array(
        'FeaturePanel'=>array('SortOrderGamesPage'=>'Int')
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

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Description'));

        //************** Caroussel Top
        $FeaturePanelFieldConfig = GridFieldConfig_RelationEditor::create();
        $FeaturePanelFieldConfig->addComponents(new GridFieldSortableRows('SortOrderGamesPage'), new GridFieldDeleteAction('unlinkrelation'));

        $FeaturePanelField = new GridField('FeaturePanel',  'FeaturePanel',  $this -> FeaturePanel(), $FeaturePanelFieldConfig);

        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;

    }

    public function FeaturePanel() {
        return $this->getManyManyComponents('FeaturePanel')->sort('SortOrderGamesPage');
    }
}

class GamesPage_Controller extends Page_Controller {

    // public function init() {
    //     Requirements::javascript("js/pages/our-story.js");
    //     parent::init();
    // }

}
