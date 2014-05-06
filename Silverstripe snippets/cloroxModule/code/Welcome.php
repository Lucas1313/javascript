<?php
/*
 * Class Welcome
 *
 * Describes the Model for a Welcome (home page)
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class Welcome extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Panel_A_Title' => 'Text',
        'Panel_A_description' => 'Text'
    );

   // static $has_one = array('HomePageSlideShow'=> 'UniversalSlideShow');

    static $has_many = array(
        'HomePageSlideShow'=> 'UniversalSlideShow'
        //"HomeTopSlides" => "HomeTopSlide",
        //"IckSlides" => "IckSlide"
    );

    static $many_many = array(
        'HomePageFeaturePanels' => 'FeaturePanel',
        "RatingsReviewsData" => "RatingsReviews",
        "TipsAndTricksData" => "TipsAndTricks"
        // "CausesAndEverythingWeLoveData" => "FeaturePanel",
        //'AlsoLikesItems' => 'AlsoLikeItem'
    );

    public static $many_many_extraFields = array(
        'HomePageFeaturePanels' => array('SortOrderHomePageFeaturePanels' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
        $fields -> removeFieldsFromTab('Root', array('TipsAndTricks'));

        $dateField = new DateField('Publication Date');

        //************** slideshows
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopSlideShowHeader', 'Top SlideShow </h3><p>This is the Homepage Top Slideshow, add a slide to build</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new HeaderField('HomePageSlideShowHeader', 'Home SlideShow </h3><p>This is the Homepage Slideshows</p><h3>'));
        $topSlidesConfig = GridFieldConfig_RelationEditor::create();
        $topSlidesConfig -> addComponent(new GridFieldSortableRows('WelcomePageSortOrder'));

        $CarouselTopSlides = new GridField('HomePageSlideShow', 'HomePageSlideShow', $this -> HomePageSlideShow(), $topSlidesConfig);

        $fields -> addFieldToTab('Root.Main', $CarouselTopSlides);

        $fields -> addFieldToTab('Root.Main', new HelpField('helpHomepage',array( __CLASS__ , 'HomePageSlideShowHeader', '')));

        //************** Caroussel Ick
        /*
        $fields -> addFieldToTab('Root.Main', new HeaderField('IckSlideShowHeader', 'Ick SlideShow </h3><p>This is the Ick Slideshow, add a Ick slide to build</p><h3>'));
        $IcktionaryConfig = GridFieldConfig_RelationEditor::create();
        $IcktionaryConfig -> addComponent(new GridFieldSortableRows('SortOrderIckSlide'));

        $IcktionarySlides = new GridField('SlidesIcktionary', 'Slides Icktionary', $this -> IckSlides(), $IcktionaryConfig);
        $fields -> addFieldToTab('Root.Main', $IcktionarySlides);

        $fields -> addFieldToTab('Root.Main', new HelpField( __CLASS__ , 'IckSlideShowHeader', ''));
        */

        //************** Universal Slide Shows /*
        /*
        $fields -> addFieldToTab('Root.Main', new HeaderField('HomePageSlideShowHeader', 'Homepage SlideShow </h3><p>This is the Homepage Slideshow. Please Use the Universal Show Admin section to create your slide show.</p><h3>'));

        $fields -> addFieldToTab('Root.Main', new HelpField( __CLASS__ , 'IckSlideShowHeader', ''));
        $field = new DropdownField('HomePageSlideShowID', 'HomePageSlideShow', UniversalSlideShow::get()->map('ID', 'Name'));
        $field->setEmptyString('(Select a Slide Show)');
        $fields -> addFieldToTab('Root.Main',$field);
        */


        //************** Ratings

        $RatingsReviews = new GridField('Ratings and Reviews', // Field name
        'Ratings and Reviews', // Field title
        $this -> RatingsReviewsData(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());

        $fields -> addFieldToTab('Root.Ratings Reviews', $RatingsReviews);

        //************** feature panels
        $FeaturePanelField = new GridField('HomePageFeaturePanels', 'HomePageFeaturePanels', $this -> HomePageFeaturePanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderHomePageFeaturePanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;

    }

    public function HomePageFeaturePanels() {
        return $this -> getManyManyComponents('HomePageFeaturePanels') -> sort('SortOrderHomePageFeaturePanels');
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }

}

class Welcome_Controller extends Page_Controller {
	 
    public function init() {

        Requirements::javascript("js/plugins/swfobject.js");
        Requirements::javascript("js/pages/home-page.js");
        Requirements::javascript("js/pages/icktionary.js");

        parent::init();
    }

}
