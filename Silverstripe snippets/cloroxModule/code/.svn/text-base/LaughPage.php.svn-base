<?php
class LaughPage extends Page {

    static $db = array(
        'Date' => 'Date',
        'Author' => 'Text',
        'Headline_Blm' => 'HTMLText',
        'Cta_Title_Blm' => 'HTMLText',
        'Cta_Content_Blm' => 'HTMLText',
        'Cta_Link_Blm' => 'HTMLText',
        'Social_Header_1' => 'Text',
        'Social_Header_2' => 'Text',
        'Social_Link_FB' => 'Text',
        'Social_Title_FB' => 'Text',
        'Social_Description_FB' => 'Text',
        'Social_Link_Twitter' => 'Text',
        'Social_Title_Twitter' => 'Text',
        'Social_Description_Twitter' => 'Text'
    );

    static $many_many = array(
        'FeaturePanel' => 'FeaturePanel',
        'AlsoLikeItems' => 'AlsoLikeItem',
    );

    static $has_one = array(
        'CarousselTop' => 'Carousel',
        'MomMomentsBlogEntry' => 'MomMomentsBlogEntry',
        'MomMomentImage' => 'Image'
    );

    public static $many_many_extraFields = array('FeaturePanel' => array('SortOrderLaughPagePanel' => 'Int'), );

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

        //$fields -> addFieldToTab('Root.Main', new TextField('Social_Link'));
        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Headline_Blm', 'Headline for the Bleachable Moment panel'));
        $fields -> addFieldToTab('Root.Main', new TextField('Cta_Title_Blm', 'Call to action Title for the Bleachable moment panel'));
        $fields -> addFieldToTab('Root.Main', new TextField('Cta_Content_Blm', 'Call to action Content for the Bleachable moment panel'));
        $fields -> addFieldToTab('Root.Main', new TextField('Cta_Link_Blm', 'Call to action Link for the Bleachable moment panel'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('SocialtHeader', 'Facebook and Twitter stuff!'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Header_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Header_2'));

        $fields -> addFieldToTab('Root.Main', new TextField('Social_Link_FB'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Title_FB'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Description_FB'));

        $fields -> addFieldToTab('Root.Main', new TextField('Social_Link_Twitter'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Title_Twitter'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Description_Twitter'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('MomMomentHeader', 'Mom Moment Panel'));
        $field = new DropdownField('CarousselTopID', 'CarousselTop', Carousel::get() -> map('ID', 'Name'));
        $field -> setEmptyString('(Select a Caroussel Top)');
        $fields -> addFieldToTab('Root.Main', $field);

        $field =  new DropdownField('MomMomentsBlogEntryID', 'MomMoments Blog Entry', MomMomentsBlogEntry::get()->map('ID', 'Excerpt'));
        $field->setEmptyString('(Select a Mom Moments Blog Entry)');
        $fields -> addFieldToTab('Root.Main',$field);

        $fields -> addFieldToTab('Root.Main', new UploadField('MomMomentImage', 'Please upload an image for the Mom Moment panel'));
        /**/

        /************** Caroussel Ick

         $fields -> addFieldToTab('Root.Main', new HeaderField('IckSlideShowHeader', 'Ick SlideShow </h3><p>This is the Ick Slideshow, add a Ick slide to build</p><h3>'));

         $IcktionarySlides = new GridField('SlidesIcktionary', 'Slides Icktionary', $this -> IckSlides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderIckSlide'), new GridFieldDeleteAction('unlinkrelation')));

         $fields -> addFieldToTab('Root.Main', $IcktionarySlides);

         $fields -> addFieldToTab('Root.Main', new HelpField('IckHelp', array( __CLASS__ , 'IckSlideShowHeader', '')));

         /**/
        //************** Feature Panel
        $featurePanelField = new GridField('FeaturePanel', 'Feature Panels', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderLaughPagePanel'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $featurePanelField);

        return $fields;
    }

    /**
     * @method FeaturePanel
     * Purpose: sorts the Feature panels
     *
     * @author Luc martin at Clorox.com
     * @version $ID
     */
    public function FeaturePanel() {
        return $this -> getManyManyComponents('FeaturePanel') -> sort('SortOrderLaughPagePanel');
    }


    /**
     * @method MomMomentsBlogEntry
     * Purpose returns a Blog entry
     * if set by CMS returns the selected MomMoment
     * else returns the last entry
     * @author Luc Martin at clorox.com
     * @version $ID
     */
    public function MomMomentsBlogEntry(){
		try{


        if(empty($this->MomMomentsBlogEntryID)){
            $momMoment = $this->MomMomentsBlogEntry;
        }else{
            $momMoment = MomMomentsBlogEntry::get()->sort('ID','DESC')->first();
        }
		$momMoment = MomMomentsBlogEntry::get()->sort('ID','DESC')->first();
        // we need the title to be split in 2 parts
        $title = $momMoment->Title;

        // transform into an array
        $titleAr = explode(' ', $title);

        // count words
        $count = count($titleAr);

        // cut in half if there is more than 4 words
        $half = ($count > 4) ? round($count/2) : $count;

        // init vars
        $title1 = '';
        $title2 = '';

        // iterate through the array
        for($n = 0 ; $n < $count ; $n++){

            // first half goes to title1
            $title1 .= ($n < $half) ? $titleAr[$n].' ' : '';

            // second half goes to title2
            $title2 .= ($n >= $half) ? $titleAr[$n].' ' : '';
        }
        // override moment with new values
        try{

        $momMoment->Title1 = $title1;
        $momMoment->Title2 = $title2;
        }catch(exception $e){
        	error_log($e);
        }


        // bye bye
        return $momMoment;
        }catch(exception $e){
        	error_log($e);
        }
    }

    public function userCanVote(){
        $BLMMasterPage = new BLMMasterPage_Controller();
        return $BLMMasterPage->userCanVote();
    }

	public function getThisWeekShowdown(){
		$BLMMasterPage = new BLMMasterPage();
		return $BLMMasterPage->getThisWeekShowdown();

	}
    public function lastVoteId(){
        $BLMMasterPage = new BLMMasterPage_Controller();
        return $BLMMasterPage->lastVoteId();
    }
	public function BLMMasterPageController(){
		$BLMMasterPageController = new BLMMasterPage_Controller();
		return $BLMMasterPageController;
	}

}

class LaughPage_Controller extends Page_Controller {

    public function init() {
        //Requirements::javascript("js/pages/icktionary.js");
        Requirements::javascript("js/plugins/jquery.turn.js");
        Requirements::javascript("js/pages/laugh-home-page.js");
        Requirements::javascript("js/pages/blm-common.js");
        parent::init();
    }

}
?>