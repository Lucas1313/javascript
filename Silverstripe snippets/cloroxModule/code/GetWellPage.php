<?php
/*
 * Class GetWellPage
 *
 * Describes the Model for a GetWellPage the page
 *
 * @author Kody Smith -at- clorox.com
 * @version $Id
 */
class GetWellPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
		'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );


    static $many_many = array(
    	'GetWellPageSlideShow'=> 'UniversalSlideShow',
		'VideoItems' => 'VideoItem'
    );

    public static $many_many_extraFields = array(
    	'GetWellPageSlideShow' => array(
    		'SortOrderGetWellPageSlideShow' => 'Int'
		),
        'VideoItems'=>array(
            'SortOrderVideoItems'=>'Int'
        )
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
         //***************** Videos
        $fields -> addFieldToTab('Root.Main', new HeaderField('Videos_for_That_Page', 'Videos in that page, usually "Bleachable Moments", and "solve" '));

        $videoField = new GridField('VideoItems', 'VideoItems', $this -> VideoItems(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderVideoItems'),
            new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(10),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter(),
            new GridFieldAddNewButton()
        ));
        $fields -> addFieldToTab('Root.Main', $videoField);

        $dateField = new DateField('Publication Date');

		$topSlidesConfig = GridFieldConfig_RelationEditor::create();

		$CarouselTopSlides = new GridField('GetWellPageSlideShow', 'GetWellPageSlideShow', $this -> GetWellPageSlideShow(), $topSlidesConfig);

        $fields -> addFieldToTab('Root.Main', $CarouselTopSlides);

        return $fields;

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

class GetWellPage_Controller extends Page_Controller {
	public $defaultVideoID;
	static $allowed_actions = array(
									'index', 'video'
    );
    public function init() {
    	
        Requirements::javascript("js/plugins/jquery.youtubewrapper.js");
        Requirements::javascript("js/plugins/jquery.videoPlayerManager.js");
        Requirements::javascript("js/pages/get-well-page.js");
        parent::init();
    }
	public function bodyID(){
		return 'get-well';
	}
	
	public function video(){
		
		if(isset($_REQUEST['video'])){
			$defaultVideoID = $_REQUEST['video'];
			return $this;
		}
		$uriArray = explode('/',$_SERVER['REQUEST_URI']);
		$endOfArray = sizeof($uriArray)-2;
		$videoName = $uriArray[$endOfArray];
		while ($uriArray[$endOfArray]=="" || $uriArray[$endOfArray]==null){
			$endOfArray = $endOfArray-1;
			$videoName =  $uriArray[$endOfArray];
		}
		if($videoName == "video"){
			return "no video specified";
		}else{
			$defaultVideoID = $uriArray[$endOfArray];
			$this->DefaultVideo = $this->getManyManyComponents('VideoItems')->byID($defaultVideoID);
			return $this;
		}
		
	}
	
	public function getVideoURL($videoID=1){
		$videoURL=$this->getURL();
		//$videoURL=$videoURL->AbsoluteLink;
		if(strpos($videoURL,'/video/')<=0){
			$videoURL.='video/'.strpos($videoURL,'/video');
		}else{
			$videoURL=explode('/',$videoURL);
			array_pop($videoURL);
			array_pop($videoURL);
			$videoURL=implode('/',$videoURL);
		}
		//$videoURL.='video/'.$this->ID;
		return $videoURL.'/';
	}
	/**
	 * getCouponURL() function
	 * @Description: This function is used to generate a CouponsInc. URL for members
	 *               receive coupons from the site. 
	 *               This function is used as a helper function to the CouponsInc Module in SS
	 * @Requirements: 
	 *        @var $offerCode = coupon offer code from Coupons Inc. 
	 *        @var $shortKey = $shortKey code from Coupons Inc. 
	 *        @var $longKey = $longKey code from Coupons Inc.
	 *        @var $refererURL = URL you would like the user to be directed back to after signin
	 * 
	 * @author:  Kody.Smith -at- clorox.com
	 * @version: 1.0
	 * @date:    12/12/2013
	 * 
	 */
	public function getCouponURL(){
		$offerCode = '113519';
		$shortKey  = 'hv5stuzplk';
		$longKey   = 'L1Kl2frxEXAQ4W5Y6ZgCMS9IyuHB8vDGNn7wzpicJsVOaedoTF3PqmhjktbUR';
		$refererURL = $this->trimURL();
		return BricksCoupon::directCouponURL($offerCode,$shortKey,$longKey,$refererURL);
	}
	/**
	 * trimURL() function
	 * @Description: This function is used to trim the first directory from the current address URL
	 * @currentURL:  'http://www.clorox.com/videos/17'
	 * @return:      '/videos/17'
	 * @requirements: getURL() function which can be substituted with php URL functions
	 * @author:  Kody.Smith -at- clorox.com
	 * @version: 1.0
	 * @date:    12/12/2013
	 * 
	 * @author:  Kody.Smith -at- clorox.com
	 * @version: 1.0
	 * @date:    12/12/2013
	 */
	public function trimURL(){
		$refererURL = $this->getURL();
		$refererURL = explode('/',$refererURL);
		array_shift($refererURL);
		$refererURL = implode('/',$refererURL);
		return $refererURL;
	}
}