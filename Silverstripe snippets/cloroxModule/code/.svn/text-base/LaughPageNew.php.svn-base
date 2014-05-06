<?php
class LaughPageNew extends Page {

    static $db = array(
        'Date' => 'Date',
        'Author' => 'Text',
        'Title' => 'Varchar',
        //'Social_Link' => 'Text',
        //'Social_Title' => 'Text',
        //'Social_Description' => 'Text'
     );

    static $many_many = array(
        'FeaturePanel' => 'FeaturePanel',
        'AlsoLikeItems' => 'AlsoLikeItem'
    );

    static $has_many = array(
        'IckSlides' => 'IckSlide'
    );

    public static $many_many_extraFields=array(
        'FeaturePanel' => array('SortOrderLaughPagePanel' => 'Int')
    );


    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        //$fields -> removeFieldFromTab('Root.Main', 'Social_Link');
        //$fields -> removeFieldFromTab('Root.Main', 'Social_Title');
        //$fields -> removeFieldFromTab('Root.Main', 'Social_Description');

        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Author'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        //$fields -> addFieldToTab('Root.Main', new TextField('Social_Link'));
        //$fields -> addFieldToTab('Root.Main', new TextField('Social_Title'));
        //$fields -> addFieldToTab('Root.Main', new TextField('Social_Description'));

         //************** Caroussel Ick

        $fields -> addFieldToTab('Root.Main', new HeaderField('IckSlideShowHeader', 'Ick SlideShow </h3><p>This is the Ick Slideshow, add a Ick slide to build</p><h3>'));

        $IcktionarySlides = new GridField('SlidesIcktionary', 'Slides Icktionary', $this -> IckSlides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderIckSlide'), new GridFieldDeleteAction('unlinkrelation')));

        $fields -> addFieldToTab('Root.Main', $IcktionarySlides);


        $fields -> addFieldToTab('Root.Main', new HelpField('IckHelp', array( __CLASS__ , 'IckSlideShowHeader', '')));

        //************** Feature Panel

        $featurePanelField = new GridField('FeaturePanel', 'Feature Panels', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderLaughPagePanel'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $featurePanelField);

        return $fields;
    }

    public function FeaturePanel() {
        return $this->getManyManyComponents('FeaturePanel')->sort('SortOrderLaughPagePanel');
    }

    public function slidesFromWelcome() {
        $ickSlidesInWelcomePage = $this -> IckSlides();
        return $ickSlidesInWelcomePage;
    }

    public function onBeforeWrite() {

        //$this -> addSlides();

        if (empty($this -> Social_Link)) {
            $this -> Social_Link = 'http://www.example.com';
        }
        if (empty($this -> Social_Title)) {
            $this -> Social_Title = 'This is the Social Title';
        }
        if (empty($this -> Social_Description)) {
            $this -> Social_Description = 'This is the Social Description';
        }
        parent::onBeforeWrite();
    }

}

class LaughPageNew_Controller extends Page_Controller {

    public function init() {
        Requirements::javascript("js/pages/icktionary.js");

        parent::init();
    }
}
?>