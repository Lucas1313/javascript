<?php
/*
 * Class OneHundredYearsPage
 * Describes the Model for a OneHundredYearsPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class OneHundredYearsPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'HtmlText',
        'Title_1' => 'HtmlText',
        'Description' => 'HtmlText'
    );

    static $many_many = array(
        "FeaturePanel" => "FeaturePanel",
         'slides' => 'HistoricalSlide'
    );

    public static $many_many_extraFields = array(
        'FeaturePanel' => array('SortOrder100YearPage' => 'Int'),
        'slides' => array('SortOrder100YearPageSlideshow' => 'Int')
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
        $fields -> addFieldToTab('Root.Main', new TextField('Description', 'Description'));

        //***************** Slide Panels

        $fields -> addFieldToTab('Root.Main', new HeaderField('historicalSlideShow', 'The History of Clorox in a slide show:'));
        $slideshowField = new GridField('slides', 'Historical Slide Show', $this -> slides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrder100YearPageSlideshow'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $slideshowField);

        //************** Caroussel Top

        $FeaturePanelFieldConfig = GridFieldConfig_RelationEditor::create();
        $FeaturePanelFieldConfig -> addComponents(new GridFieldSortableRows('SortOrder100YearPage'), new GridFieldDeleteAction('unlinkrelation'));

        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), $FeaturePanelFieldConfig);

        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;

    }

    public function FeaturePanel() {
        return $this -> getManyManyComponents('FeaturePanel') -> sort('SortOrder100YearPage');
    }

    public function slides() {
            return $this -> getManyManyComponents('slides') -> sort('SortOrder100YearPageSlideshow');
        }
}

class OneHundredYearsPage_Controller extends OurStoryPage_Controller {

    public function init() {

        Requirements::javascript("js/pages/centennial-page.js");
        parent::init();
    }

}
