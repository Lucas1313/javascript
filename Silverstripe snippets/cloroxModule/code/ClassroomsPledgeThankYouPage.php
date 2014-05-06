<?php
/*
 * Class CLTLandingPage
 *
 * Describes the Model for a CLTLandingPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsPledgeThankYouPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Panel_A_Title' => 'Text',
        'Panel_A_description' => 'Text',
 		'PledgeCount' => 'Int'
    );

 

    static $many_many = array(
        "ClassroomsPageFeaturePanels" => "FeaturePanel",
    );

    public static $many_many_extraFields = array(
        'ClassroomsPageFeaturePanels' => array('SortOrderClassroomsPageFeaturePanels' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
        $fields -> removeFieldsFromTab('Root', array('TipsAndTricks'));

        $dateField = new DateField('Publication Date');

        //************** slideshows
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopSlideShowHeader', 'Top SlideShow </h3><p>This is the ClassroomsPage Top Slideshow, add a slide to build</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new HeaderField('ClassroomsPageSlideShowHeader', 'Classrooms SlideShow </h3><p>This is the ClassroomsPage Slideshows</p><h3>'));
        $topSlidesConfig = GridFieldConfig_RelationEditor::create();
        $topSlidesConfig -> addComponent(new GridFieldSortableRows('ClassroomsLandingPageSortOrder'));


      //  $fields -> addFieldToTab('Root.Main', new HelpField('helpClassroomsPage',array( __CLASS__ , 'ClassroomsPageSlideShowHeader', '')));

      

        //************** feature panels
        $FeaturePanelField = new GridField('ClassroomsPageFeaturePanels', 'ClassroomsPageFeaturePanels', $this -> ClassroomsPageFeaturePanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderClassroomsPageFeaturePanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
		
		
		
		
		/************* feature product for classrooms
		$ClassroomsPromoProductField = new GridField('ClassroomsPromoProduct', // Field name
        'ClassroomsPromoProduct', // Field title
        $this -> ClassroomsPromoProduct(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());
		
		$fields -> addFieldToTab('Root.FeatureProduct', $ClassroomsPromoProductField);
		*/
		
        return $fields;

    }
	public function isMember(){
		return Member::currentUserID();
	}
	
    public function ClassroomsPageFeaturePanels() {
        return $this -> getManyManyComponents('ClassroomsPageFeaturePanels') -> sort('SortOrderClassroomsPageFeaturePanels');
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
	public function BackURL() { 
      if(isset($_REQUEST['BackURL'])) { 
         return $_REQUEST['BackURL']; 
      } else { 
         return Session::get('BackURL'); 
      } 
   } 
	public function getPledgeCount(){
		$count = DB::query("SELECT COUNT(*) FROM ClassroomsPledge")->value();
		return $count;
	}
	public function takePledge(){
				
					$newRecord = new ClassroomsPledge;
	//	$newRecord->memberID = $member->ID;
					$newRecord->write();
	//	$this->sendThankYouEmail($member->Email);
		//return 'New Record!'.$this->PledgeCount;
		return null;
			
				}
				
	public function printCoupon(){
		if($member = Member::currentUser()){
			if(strlen($member->Email) >= 6){
				$this->sendThankYouEmail($member->Email);
				return $this->pledgeCouponURL();
			}
		}else{
			$BackURL = $this->BackURL();
			$Link = $BackURL;
			//$_SESSION['setBackURL'] = urlencode($Link);
			//$_SESSION['setBackURL'] = $Link;
			//Director::redirect(Director::baseURL() . $Link);
			$Link = urlencode($Link); 
			return "/sign-in/?BackURL=".$Link;
		}
	}
	
	/**
	 * Function:    bricksCouponURL 
	 * Description: This function is used to call back a dynamic coupon URL when the user is logged in
	 * 
	 * Inputs:      $shortKey, $logKey, $offerCode   // all of these values are given by bricks coupon and just need to be passed
	 * Output:		example url: http://bricks.coupons.com/enable.asp?o=113519&c=CL&p=abc3123lkjasdlj3&cpt=zkM1BQPE1xOCeE61c4yqho
	 * 
	 * Author:      Kody Smith @ Clorox
	 * Version:     1.0
	 * Date:        8/8/2013
	 * Notes:       additional instructions about this can be found in the cloroxModule/code/brinksCoupon.php
	 */
	
	function referCouponURL(){
		
		$offerCode = '113521';
		$shortKey  = 'hv5stuzplk';
		$longKey   = 'L1Kl2frxEXAQ4W5Y6ZgCMS9IyuHB8vDGNn7wzpicJsVOaedoTF3PqmhjktbUR';
		$member    = Member::currentUser();
		if( Member::currentUserID() ) {
    		// Yes!
    		
	    	$clientID  = $member->ID;
			$URL = new bricksCoupon;
			//return $URL->encodedURL($offerCode,$shortKey,$longKey,$clientID);
			return 'clorox-classrooms-canisters/thanks-for-sharing';	
		} else {
		    // No!
		    $signInUrl = '/sign-in/?BackURL=/take-the-pledge/thank-you/?c=0';
			$statusCode = 303;
			header('Location: ' . $signInUrl, true, $statusCode);
   			die();
		    //Director::redirect('/sign-in/?backURL=/ take-the-pledge/thank-you/?c=0');
		}
	}
	function pledgeCouponURL(){
		$offerCode = '113519';
		$shortKey  = 'hv5stuzplk';
		$longKey   = 'L1Kl2frxEXAQ4W5Y6ZgCMS9IyuHB8vDGNn7wzpicJsVOaedoTF3PqmhjktbUR';
		$member    = Member::currentUser();
		if( Member::currentUserID() ) {
    		// Yes!
    		$this->takePledge(); // if the user is logged in then they took the pledge... else login and come back to count it once.
	    	//$clientID  = $member->ID;
			//$URL = new bricksCoupon;
			//return $URL->encodedURL($offerCode,$shortKey,$longKey,$clientID);
			return 'clorox-classrooms-canisters/cdw';	
		} else {
		    // No!
		    $signInUrl = '/sign-in/?BackURL=/take-the-pledge/thank-you/?c=0';
			$statusCode = 303;
			header('Location: ' . $signInUrl, true, $statusCode);
   			die();
		    //Director::redirect($signInUrl);
		}
	}
	function getMemberName(){
		$member    = Member::currentUser();
		return $member->ID;
	}
	function sendThankYouEmail($email){
		if ($GLOBALS['currentEnvironment'] == 'ENV_PRODUCTION') {
			$campaign = 42315291;
		} else {
			$campaign = 42315291;
		}
		$member    = Member::currentUser();
		$NAME_FIRST = ucfirst($member->FirstName);
		$NAME_LAST  = ucfirst($member->SurName);
		//$referrer_name = $member->getFirstName() . ' ' . $member->getLastName();
		$email = $member->email;
		$link = "http://www.clorox.com/classrooms/take-the-pledge/thank-you/";
		
		if(strLen($email)>6){
			CCL_SilverpopTransact::generate(
		
				$campaign, //campaign ID is the ID of CL_Classrooms_Pledge
				$email,
				array(
						'NAME_FIRST'=>$NAME_FIRST,
						'NAME_LAST'=>$NAME_LAST,
						'EMAIL'=>$email,
						'LINK'=>$link,
				)
			);
		}
	}
	function referComplete(){
		if(isset($_REQUEST['c']) && $_REQUEST['c'] == 1){
			
			return true;
		}else{
			return false;
		}
	}
	function inputError($message=''){
		if(isset($_REQUEST['e']) && $_REQUEST['e'] == 1){
			return "<span class='error'>$message</span>";
		}else{
			return '';
		}
	}
}

class ClassroomsPledgeThankYouPage_Controller extends Page_Controller {

    public function init() {
    	Requirements::javascript("js/pages/ClassroomsPage.js");
		Requirements::javascript("js/pages/CLTLandingPage.js");
		Requirements::javascript("js/pages/takeThePledgePage.js");
		Session::set('BackURL',$this->Link());
		
        parent::init();
    }
 	public static $allowed_actions = array(
        'index',
        'referPledgeForm',
        'referPledgeAction'
    );
	
	/**
     * referPledgeForm function.
     * This creates the referPledge form
     * 
     * @return void
     */
    public function referPledgeForm(){
        // the fields
        $emailField1 = EmailField::create('Email1', 'Email Address');
		$emailField2 = EmailField::create('Email2', 'Email Address');
		$emailField3 = EmailField::create('Email3', 'Email Address');
		
        // the form
        $referPledgeForm =  new Form(
            $this, 'referPledgeForm', 
            // build one FieldList with the initial form fields
            // http://doc.silverstripe.org/framework/en/reference/form-field-types
            new FieldList(
                $emailField1,
                $emailField2,
                $emailField3
            ), 
            // one fieldlist holds the call to the form action (method)
            // and the text on the signup button
            new FieldList(
                
                // List the action buttons here
                new FormAction('referPledgeAction', 'Share to get your extra coupon')
            ), 
            // List the required fields here: "Email", "FirstName"
            new RequiredFields(
               'Email1',
               'Email2',
               'Email3'
               
            )
        );
        return $referPledgeForm;
    }
	/**
     * referPledge function.
     *
     * This processes the Refer_Pledge_form and 
     * sends the user an email with their token  
     * 
     * @return void
     */
    public function referPledgeAction(){
       	$copyrightYear = date("Y");
		
		if ($GLOBALS['currentEnvironment'] == 'ENV_PRODUCTION') {
			$campaign = 42315291;
		} else {
			$campaign = 42315291;
		}
		$member    = Member::currentUser();
		$NAME_FIRST = ucfirst($member->FirstName);
		$NAME_LAST  = ucfirst($member->SurName);

		$email = $member->email;
		$link = "http://www.clorox.com/classrooms/take-the-pledge/thank-you/";
		$emailInput;
		if(isset($_REQUEST['Email1'])){$emailInput[1] = $_REQUEST['Email1'];};
		if(isset($_REQUEST['Email2'])){$emailInput[2] = $_REQUEST['Email2'];};
		if(isset($_REQUEST['Email3'])){$emailInput[3] = $_REQUEST['Email3'];};
		if(strlen($emailInput[1]) <= 6 || strlen($emailInput[2]) <= 6 || strlen($emailInput[3]) <= 6 ){
			$signInUrl = '/take-the-pledge/thank-you/?c=0&e=1 #saveEvenMorePanel:'.$emailInput[1];
			$statusCode = 303;
			header('Location: ' . $signInUrl, true, $statusCode);
   			die();
		}else{
			foreach($emailInput as $email){
				if(strlen($email)>=6){
					CCL_SilverpopTransact::generate(
				
						$campaign, //campaign ID is the ID of CL_Classrooms_Pledge
						$email,
						array(
								'NAME_FIRST'=>$NAME_FIRST,
								'NAME_LAST'=>$NAME_LAST,
								'EMAIL'=>$email,
								'LINK'=>$link,
						)
					);
				}
			}	
			$this->redirect('/take-the-pledge/thank-you/?c=1');		
		}


        
    }
	
}

