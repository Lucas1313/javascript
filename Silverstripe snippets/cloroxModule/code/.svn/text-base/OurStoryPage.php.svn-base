<?php
/*
 * Class OurStoryPage
 * Describes the Model for a OurStoryPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class OurStoryPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        // 'Title' => 'HTMLText',
        'Title_1' => 'HTMLText',
        'Description' => 'HTMLText'
    );

     /*1. set relationship*/
    static $many_many = array(
        "FeaturePanel" => "FeaturePanel",
         /*2. name panels, map to UniversalSlideShow*/
        "OurStoryPageSlideShows" => "UniversalSlideShow");

    public static $many_many_extraFields=array(
        'FeaturePanel'=>array('SortOrderOurStoryPage'=>'Int'),
        'OurStoryPageSlideShows' => array('SortOrderOurStoryPageSlideShows' => 'Int') /*2b added here*/
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

        /*3. add data grid*/
        //************** Universal Slide Show
        $fields -> addFieldToTab('Root.Main', new HeaderField('OurStoryPageSlideShowsHeader', 'Our Story SlideShow </h3><p>This are the Causes slides. Please Use the Universal Show Admin section to create your slide show.</p><h3>'));

        $Field = new GridField('OurStoryPageSlideShows', 'OurStoryPageSlideShows', $this -> OurStoryPageSlideShows(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderOurStoryPageSlideShows'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $Field);

        //************** Caroussel Top

        $FeaturePanelFieldConfig = GridFieldConfig_RelationEditor::create();
        $FeaturePanelFieldConfig->addComponents(new GridFieldSortableRows('SortOrderOurStoryPage'), new GridFieldDeleteAction('unlinkrelation'));

        $FeaturePanelField = new GridField('FeaturePanel',  'FeaturePanel',  $this -> FeaturePanel(), $FeaturePanelFieldConfig);

        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;

    }

     /*5. create function for slideshow creation*/
    public function OurStoryPageSlideShows() {
        return $this -> getManyManyComponents('OurStoryPageSlideShows') -> sort('SortOrderOurStoryPageSlideShows');
    }

    public function FeaturePanel() {
        return $this->getManyManyComponents('FeaturePanel')->sort('SortOrderOurStoryPage');
    }
	public function historicalSlide(){
		$historyPage = OneHundredYearsPage::get()->first();
		//error_log(print_r($historyPage,1));
		$historicalSlides = $historyPage->slides();
		return $historicalSlides;
	}
}

class OurStoryPage_Controller extends Page_Controller {

    public function init() {
        Requirements::javascript("js/pages/our-story.js");
		Requirements::javascript("js/pages/centennial-page.js");
        parent::init();
    }

}
