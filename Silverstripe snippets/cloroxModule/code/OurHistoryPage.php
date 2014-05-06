<?php
/*
 * Class OurHistoryPage
 *
 * Describes the Model for a Our History Page
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class OurHistoryPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Title_1' => 'Varchar',
        'Title_2' => 'Varchar',
        'Slogan' => 'Text',
    );

    static $has_many = array(
        'HistoryPanel' => 'HistoryPanel',
        'UseFor' => 'UseFor',
        'FaqCategory' => 'FaqCategory'
    );
    static $many_many = array("FeaturePanel" => "FeaturePanel", 'HistorySlideShows' => 'UniversalSlideShow');

    static $belong_many_many = array('OurHistoryPage' => 'OurHistoryPage');

    public static $many_many_extraFields = array(
        'HistorySlideShows' => array('SortOrderHistorySlideShows' => 'Int')
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

         //************** Universal Slide Show
        $fields -> addFieldToTab('Root.Main', new HeaderField('HistoryPageSlideShowHeader', 'History SlideShow </h3><p>This are the History Slideshows. Please Use the Universal Show Admin section to create your slide show.</p><h3>'));

        $Field = new GridField('HistorySlideShows', 'HistorySlideShows', $this -> HistorySlideShows(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderHistorySlideShows'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $Field);

        //***************** Feature Panels
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

    public function HistorySlideShows() {
        return $this -> getManyManyComponents('HistorySlideShows') -> sort('SortOrderHistorySlideShows');
    }

}

class OurHistoryPage_Controller extends Page_Controller {

    public function init() {
        Requirements::javascript("js/pages/our-history.js");
        parent::init();
    }

}
