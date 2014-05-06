<?php
/*
 * Class BLMLandingPage
 * Describes the Model for a BLMLandingPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMLandingPage extends BLMMasterPage {
    static $db = array(
        'QuantityOfMomentsToManage' => 'int',
        'RefreshMomentsInDatabase' => 'Boolean',
        'ShowStartingDate' => 'SS_Datetime',
        'ShowEndingDate' => 'SS_Datetime',
        'ShowOnlyPending' => 'Boolean',
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText',
        'SharingTitle' => 'Varchar(75)',
        'SharingPromoText' => 'Varchar(275)',
        'HelpChooseWinnerTitle' => 'Varchar(75)',
        'HelpChooseWinnerContent' => 'HTMLText',
        'ChooseWinnerCTATitle1' => 'Text',
        'ChooseWinnerCTALink1' => 'Text',
        'ChooseWinnerCTATitle2' => 'Text',
        'ChooseWinnerCTALink2' => 'Text',
    );
    public static $has_one = array(
        'RulesPDF'=>'File',
    );
    public static $has_many = array(
        'BLMSolves' => 'BLMSolve',
        'BLMTips' => 'BLMTip',
        'BLMoments' => 'BLMoment'
    );

    public static $many_many = array();
    public static $belongs_many_many = array();

    public function getCMSFields() {
        $this -> BLMomentsGet();
        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description', 'Description'));

        //***************** Sharing
        $fields -> addFieldToTab('Root.Main', new HeaderField('SharingHeader', 'Sharing'));
        $fields -> addFieldToTab('Root.Main', new TextField('SharingTitle'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('SharingPromoText', 'SharingPromoText'));

        //***************** Choose winners
        $fields -> addFieldToTab('Root.Main', new HeaderField('ChoosingWinnersHeader', 'Help Choose Your winners section'));
        $fields -> addFieldToTab('Root.Main', new TextField('HelpChooseWinnerTitle', 'Title'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('HelpChooseWinnerContent', 'Choose your winners promo text'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTATitle1', 'Button "Week Vote" title'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTALink1', 'Button "Week Vote" Link'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTATitle2', 'Button "Vote for Moment in the gallery" title'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTALink2', 'Button "Vote for Moment in the gallery" link'));

        $fields -> addFieldToTab('Root.ManageMoments', new TextField('QuantityOfMomentsToManage', 'How many moments would you like to manage? (Saving required) Max 200'));
        $fields -> addFieldToTab('Root.ManageMoments', new CheckboxField('RefreshMomentsInDatabase', 'Would you like to refresh the database?'));
        $fields -> addFieldToTab('Root.ManageMoments', new CheckboxField('ShowOnlyPending', 'Show Only Pending?'));

        $dateField = new DateField('ShowStartingDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.ManageMoments', $dateField);

        $dateField = new DateField('ShowEndingDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.ManageMoments', $dateField);
          //***************** Feature Panels
        $BLMomentField = new GridField('BLMoment', 'BLMoment', $this -> BLMomentsGet(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.ManageMoments', $BLMomentField);

        $upload = new UploadField('RulesPDF');
        $upload->setConfig('allowedMaxFileNumber', 1);
        $upload->getValidator()->setAllowedExtensions(array('pdf'));
        $fields->addFieldToTab('Root.Rules', $upload);

        //**
        $fields -> addFieldToTab('Root.ManageMoments', new NestedDataObjectField('BLMoments', 'This BLMoments:', array(
            'parent' => $this,
            'parentClass' => 'BLMLandingPage',
            'object' => 'BLMomentsGet',
            'objectClass' => 'BLMoment',
            'titleFields' => array(
                'Name',
                'created_timestamp'
            ),
            'showClassInTitle' => false,
            'fields' => array(
                'Name' => 'TextField',
                'submission' => 'TextAreaField',
                'approval' => 'OptionsetField',
                'solved' => 'TextAreaField'
            ),
            'optionsSetSources' => array('approval' => array(
                    'APPROVED' => 'APPROVED',
                    'REJECTED' => 'REJECTED',
                    'PENDING' => 'PENDING'
                )),
            'parentId' => $this -> ID,
            'addImageInfo' => null,
            'recursions' => array()
        )));

        //***************** Tips
        $BLMTipsField = new GridField('BLMTips', 'BLMTips', $this -> BLMTips(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('TipSortOrder'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMTipsField);

        //***************** Solves
        $BLMSolvesField = new GridField('BLMSolves', 'BLMSolves', $this -> BLMSolves(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SolveSortOrder'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMSolvesField);

        return $fields;

    }
    function BLMomentsGet() {
    	try{
        if (!empty($this -> ShowStartingDate)) {
            //error_log('there is a date set ' . date('U', strtotime($this -> ShowStartingDate)));
            $dateFrom = date('U', strtotime($this -> ShowStartingDate));
        }
        if (!empty($this -> ShowEndingDate)) {
            //error_log('there is a date set ' . date('U', strtotime($this -> ShowEndingDate)));
            $dateTo = date('U', strtotime($this -> ShowEndingDate));
        }

        if (empty($this -> QuantityOfMomentsToManage)) {
            $this -> QuantityOfMomentsToManage = 10;
        }
        if ($this -> QuantityOfMomentsToManage >= 500) {
            $this -> QuantityOfMomentsToManage = 200;
        }

        if ($this -> RefreshMomentsInDatabase == true) {

            set_time_limit(900);

            $allMoments = BLMoment::get() -> sort('ID', 'DESC') -> limit(20000);

            foreach ($allMoments as $key => $moment) {
                //error_log('adding moment');
                $this -> BLMoments() -> add($moment);
            }
            $this -> RefreshMomentsInDatabase = false;
        }
        if (!empty($dateFrom) && !empty($dateTo)) {
            if ($this -> ShowOnlyPending == true) {

                return $this -> BLMoments() -> where('`approval`=\'PENDING\' AND `created_timestamp` >= STR_TO_DATE(\'' . $this -> ShowStartingDate . '\', \'%Y-%m-%d %H:%i:%s\') AND `created_timestamp` <=  STR_TO_DATE(\'' . $this -> ShowEndingDate . '\', \'%Y-%m-%d %H:%i:%s\') ') -> sort(array('ID' => 'DESC')) -> limit($this -> QuantityOfMomentsToManage);

            }
            return $this -> BLMoments() -> where('`created_timestamp` >= STR_TO_DATE(\'' . $this -> ShowStartingDate . '\', \'%Y-%m-%d %H:%i:%s\') AND `created_timestamp` <=  STR_TO_DATE(\'' . $this -> ShowEndingDate . '\', \'%Y-%m-%d %H:%i:%s\') ') -> sort(array('ID' => 'DESC')) -> limit($this -> QuantityOfMomentsToManage);
        }
        if ($this -> ShowOnlyPending == true) {

            return $this -> BLMoments() -> filter(array('approval' => 'PENDING')) -> sort(array('ID' => 'DESC')) -> limit($this -> QuantityOfMomentsToManage);

        }
        return $this -> BLMoments() -> sort(array('ID' => 'DESC')) -> limit($this -> QuantityOfMomentsToManage);
		}catch(exception $e){
			error_log($e);
		}
    }

    /**
     * Method called before an Object is saved

     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        // Update all changes in the Nested Objects
        NestedDataObjectField::updateNestedDataObjects($this);
    }

    /*** 5) Add new Nested Objects ***/

    /**
     * Method called After an Object is saved

     * @param none
     * @return void
     */

    function onAfterWrite() {
        parent::onAfterWrite();
        // Saves New NestedDataObjects
        NestedDataObjectField::generateNewNestedDataObjectItem($this);

    }
     /**
     * function updateNestedDataObjects
     * Method to update a the contain of NestedDataObjects
     * Will update data when the fields representing the newNestedObject are changed
     *
     * @author Luc Martin
     * @version $ID
     */
    public function updateNestedDataObjects(&$caller, $objectToSaveClass = null) {
		try{
        // test if we have any object in this

        if (isset($_SESSION['nestedObjects']) && count($_SESSION['nestedObjects']) > 1) {

            // iterate through the saved data
            foreach ($_SESSION['nestedObjects'] as $key => $nestedObject) {
                // test if there is a nested object
                if (count($nestedObject) > 1) {

                    // the parent
                    $parent = $nestedObject['parent'];

                    // the object
                    $objectToSave = $nestedObject['object'];

                    if($objectToSaveClass == null){
                        $objectToSaveClass = $nestedObject['objectClass'];
                    }


                    // the specific ID of the object
                    $objectId = $nestedObject['objectId'];
                    //error_log('The Object ID is :'.$objectId);
                    // now the fields
                    $fieldType = $nestedObject['field'];

                    // the local field where the user can edit data
                    $objectLocalField = $nestedObject['localField'];
                    $localFieldId = $nestedObject['localFieldId'];

                    //error_log('$objectLocalField '.$objectLocalField.' CALLER NAME '.$caller->Title);

                    // the data in the field
                    $objectNewData = $caller -> $objectLocalField;

                    //error_log('$objectNewData '.$objectNewData);
                    //error_log(print_r($caller -> BLMLandingPage_12374_BLMomentsGet_572_NEW_Name, 1));
                    // get the object from the database for update
                    $dbObject = $objectToSaveClass::get() -> filter(array('ID' => $objectId)) -> first();
                    //error_log('$dbObject name is '.$dbObject->Name);
                    // there is a Unlink checkbox in the fields so the user can unlink the object
                    $UnlinkFieldName = $localFieldId . 'Unlink';

                    $Unlink = $caller -> $UnlinkFieldName;
                    // test if the checkbox is clicked
                    if ($Unlink == 1) {
                        // if clicked we remove the link in between the parent and the object
                        $parent -> $objectToSave() -> remove($dbObject);
                    }
                    elseif (!empty($objectNewData) && $objectNewData !== '') {
                        // update the object with the new data
                        $dbObject -> $fieldType = $objectNewData;
                        // write the object
                        $dbObject -> write();
                    }
                }
            }
        }
        // Switch to get a clean session object next time
        $_SESSION['reset'] = true;
		}catch(exception $e){
			error_log($e);
		}
    }

	
}

class BLMLandingPage_Controller extends BLMMasterPage_Controller {

    //action to redirect to the rules pdf
	public static $allowed_actions = array(
		'rules',
		'MomentForm',
		'ShareMomentAction',
		'index'
	);
	public function index(){
		return $this;
	}
    /**
     * rules function
     * purpose: Will redirect the user toward the uploaded PDF page
     * @author Luc Martin -at- Clorox
     * @version $ID
     */
	public function rules(){
	    $ret = '';
		//
        $page = BLMLandingPage::get()->first();

        $f = File::get()->filter(array('ID'=>$page->RulesPDFID))->first();

        // The PDF source is in original.pdf
        Director::redirect ("http://".$_SERVER['HTTP_HOST']."/".$f->Filename);
	}

    /**
     * function init
     * get all requirements for the page
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function init() {
        Requirements::javascript("js/plugins/jquery.FastEllipsis.js");
        Requirements::javascript("js/pages/blm-landing-page.js");
        Requirements::javascript("js/plugins/mass-relevance-twitter.js");
        
        parent::init();
    }

	/*
	 * MomentForm function
	 * Returns SS Form for submitting a bleachable moment
	 *
	 * @author matt.ernst -at- clorox.com
	 * @version $ID
	 */
	public function MomentForm() {
		$moment = TextareaField::create('Moment');
		$fields = new FieldList($moment);
		$actions = new FieldList(new FormAction('ShareMomentAction', 'Share'));

		$mform = new Form($this, 'MomentForm', $fields, $actions);
		// CSRF token fails for submission box embedded on voting page;
		// this is not sensitive data, so disable security check to prevent
		// "your session timed out" //errors when submitting from vote page
		$mform->disableSecurityToken();

		return $mform;
        }

	 /*
	 * Handles a submitted bleachable moment by either attempting to store
	 * it or first redirecting the user for login/registration and
	 * simultaneous moment submission.
	 *
	 * @param $data form data submitted by the user
	 * @param $form an instance of the SS Form presented to the user
	 * @author matt.ernst -at- clorox.com
	 * @version $ID
	 */
	public function ShareMomentAction($data, $form) {
		$uid = Member::currentUser();
		//error_log(':::::::: current user id ::::::'.$uid);
		// get the raw text of the moment as submitted by the user
		$moment = '';
		if (isset($data['Moment'])) {
			$moment = $data['Moment'];
		}
		// try to submit immediately if user is logged in
		if (!empty($uid->ID)) {

			$member = Member::currentUser();

            // This is the correct to load consumer using ID (per Leon'a advice)
            $consumer = new CCL_PC_Model_Consumer ();
            $consumer->load($member->pc_consumer_id);
            $email = $consumer->getEmailAddress();

			if (!empty($email)) {
                // this function is located n the BLMMasterPage.php
				$status = $this->createSingleMoment($moment,
													$member->pc_consumer_id);

                // prepare the redirect to the thank page
				$destination = '/laugh/bleach-it-away/thanks';
				$this->redirect($destination . '?status=' . $status);

			}
			// there is a member but somehow the member was not associated with a PC_consumer.
			// this would happen if a admin uses a admin password.
			else{
			    $member->logOut();
			    $encoded = urlencode($moment);
                $this->redirect('/laugh/bleach-it-away/sign-up?moment=' . $encoded);
			}
		}

		// if user is logged out, encode moment and pass it as query
		// string parameter to the login/registration page where the user
		// is redirected
		else {
			$encoded = urlencode($moment);
			$this->redirect('/laugh/bleach-it-away/sign-up?moment=' . $encoded);
		}

		return;
	}


}
